<?php
// filepath: c:\xampp\htdocs\Explorador-IA\app\Livewire\Chat\ChatIndex.php

namespace App\Livewire\Chat;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use App\Models\Chatgroup;
use App\Models\Chat;
use App\Models\File;
use OpenAI\Laravel\Facades\OpenAI;
use PhpOffice\PhpWord\IOFactory as WordIOFactory;
use PhpOffice\PhpSpreadsheet\IOFactory as SpreadsheetIOFactory;
use Smalot\PdfParser\Parser as PdfParser;

class ChatIndex extends Component
{
    use WithFileUploads;

    public $message = '';
    public $chatgroup_id;
    public $chatgroup;
    public $messages = [];
    public $isLoading = false;
    public $errorMessage = '';
    public $images = [];
    public $previewImages = [];
    public $documents = [];
    public $previewDocuments = [];

    protected $listeners = ['messageUpdated' => 'loadMessages'];

    protected $rules = [
        'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048', // 2MB máximo
        'documents.*' => 'file|mimes:pdf,doc,docx,txt,xls,xlsx,ppt,pptx,rtf,csv|max:10240', // 10MB máximo
        'images' => 'max:5', // Máximo 5 imágenes
        'documents' => 'max:5', // Máximo 5 documentos
    ];

    public function messages()
    {
        return [
            'images.max' => 'No puedes subir más de 5 imágenes a la vez.',
            'documents.max' => 'No puedes subir más de 5 documentos a la vez.',
            'images.*.max' => 'Cada imagen no puede superar los 2MB.',
            'documents.*.max' => 'Cada documento no puede superar los 10MB.',
            'images.*.image' => 'Solo se permiten archivos de imagen.',
            'images.*.mimes' => 'Las imágenes deben ser de formato: JPG, PNG, GIF o WebP.',
            'documents.*.mimes' => 'Los documentos deben ser de formato: PDF, DOC, DOCX, TXT, XLS, XLSX, PPT, PPTX, RTF o CSV.',
        ];
    }

    public function mount()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Crear o recuperar el chatgroup del usuario
        $this->chatgroup = Chatgroup::firstOrCreate([
            'user_id' => Auth::id(),
        ], [
            'name' => 'Chat con IA - ' . Auth::user()->name
        ]);
        
        $this->chatgroup_id = $this->chatgroup->id;
        $this->loadMessages();
    }

    public function loadMessages()
    {
        $this->messages = Chat::where('chatgroup_id', $this->chatgroup_id)
            ->with(['emisor', 'files'])
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function($chat) {
                return [
                    'id' => $chat->id,
                    'message' => $chat->message,
                    'emisor_id' => $chat->emisor_id,
                    'receiver' => $chat->receiver,
                    'created_at' => $chat->created_at,
                    'emisor_name' => $chat->emisor ? $chat->emisor->name : 'IA',
                    'files' => $chat->files->map(function($file) {
                        return [
                            'id' => $file->id,
                            'name' => $file->name,
                            'type' => $file->type,
                            'url' => $file->url,
                            'mime_type' => $file->mime_type,
                            'size' => $file->formatted_size,
                            'is_image' => $file->isImage()
                        ];
                    })->toArray()
                ];
            })
            ->toArray();
    }

    public function updatedMessage()
    {
        // Limpiar mensaje de error cuando el usuario empiece a escribir
        if (!empty(trim($this->message))) {
            $this->errorMessage = '';
        }
    }

    public function updatedImages()
    {
        $this->validateOnly('images.*');
        $this->previewImages = [];
        
        foreach ($this->images as $image) {
            if ($image) {
                $this->previewImages[] = [
                    'name' => $image->getClientOriginalName(),
                    'size' => $this->formatBytes($image->getSize()),
                    'url' => $image->temporaryUrl(),
                ];
            }
        }
    }

    public function removeImage($index)
    {
        unset($this->images[$index]);
        unset($this->previewImages[$index]);
        $this->images = array_values($this->images);
        $this->previewImages = array_values($this->previewImages);
    }

    public function clearImages()
    {
        $this->images = [];
        $this->previewImages = [];
    }

    public function updatedDocuments()
    {
        $this->validateOnly('documents.*');
        $this->previewDocuments = [];
        
        foreach ($this->documents as $document) {
            if ($document) {
                $this->previewDocuments[] = [
                    'name' => $document->getClientOriginalName(),
                    'size' => $this->formatBytes($document->getSize()),
                    'type' => $document->getClientOriginalExtension(),
                    'mime' => $document->getClientMimeType()
                ];
            }
        }
    }

    public function removeDocument($index)
    {
        unset($this->documents[$index]);
        unset($this->previewDocuments[$index]);
        $this->documents = array_values($this->documents);
        $this->previewDocuments = array_values($this->previewDocuments);
    }

    private function resizeImageForOpenAI($imageContent, $mimeType, $maxWidth = 1024, $maxHeight = 1024)
    {
        try {
            // Crear imagen desde el contenido
            switch ($mimeType) {
                case 'image/jpeg':
                    $image = imagecreatefromstring($imageContent);
                    break;
                case 'image/png':
                    $image = imagecreatefromstring($imageContent);
                    break;
                case 'image/gif':
                    $image = imagecreatefromstring($imageContent);
                    break;
                case 'image/webp':
                    $image = imagecreatefromstring($imageContent);
                    break;
                default:
                    return $imageContent; // Si no se puede procesar, devolver original
            }

            if (!$image) {
                return $imageContent;
            }

            // Obtener dimensiones originales
            $originalWidth = imagesx($image);
            $originalHeight = imagesy($image);

            // Calcular nuevas dimensiones manteniendo aspecto
            $ratio = min($maxWidth / $originalWidth, $maxHeight / $originalHeight);
            
            // Si la imagen ya es pequeña, no redimensionar
            if ($ratio >= 1) {
                imagedestroy($image);
                return $imageContent;
            }

            $newWidth = intval($originalWidth * $ratio);
            $newHeight = intval($originalHeight * $ratio);

            // Crear nueva imagen redimensionada
            $newImage = imagecreatetruecolor($newWidth, $newHeight);
            
            // Preservar transparencia para PNG y GIF
            if ($mimeType === 'image/png' || $mimeType === 'image/gif') {
                imagealphablending($newImage, false);
                imagesavealpha($newImage, true);
                $transparent = imagecolorallocatealpha($newImage, 255, 255, 255, 127);
                imagefill($newImage, 0, 0, $transparent);
            }

            // Redimensionar
            imagecopyresampled($newImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, $originalWidth, $originalHeight);

            // Capturar imagen redimensionada
            ob_start();
            switch ($mimeType) {
                case 'image/jpeg':
                    imagejpeg($newImage, null, 85); // Calidad 85%
                    break;
                case 'image/png':
                    imagepng($newImage, null, 6); // Compresión nivel 6
                    break;
                case 'image/gif':
                    imagegif($newImage);
                    break;
                case 'image/webp':
                    imagewebp($newImage, null, 85);
                    break;
            }
            $resizedContent = ob_get_contents();
            ob_end_clean();

            // Limpiar memoria
            imagedestroy($image);
            imagedestroy($newImage);

            Log::info('Imagen redimensionada', [
                'original_size' => strlen($imageContent),
                'resized_size' => strlen($resizedContent),
                'original_dimensions' => "{$originalWidth}x{$originalHeight}",
                'new_dimensions' => "{$newWidth}x{$newHeight}"
            ]);

            return $resizedContent;

        } catch (\Exception $e) {
            Log::error('Error al redimensionar imagen', ['error' => $e->getMessage()]);
            return $imageContent; // Devolver original si hay error
        }
    }

    private function extractDocumentContent($filePath, $mimeType)
    {
        try {
            Log::info('Intentando extraer contenido del documento', ['path' => $filePath, 'mime' => $mimeType]);
            
            switch ($mimeType) {
                case 'text/plain':
                    return file_get_contents($filePath);
                    
                case 'application/pdf':
                    $parser = new PdfParser();
                    $pdf = $parser->parseFile($filePath);
                    return $pdf->getText();
                    
                case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document': // .docx
                case 'application/msword': // .doc
                    try {
                        $phpWord = WordIOFactory::load($filePath);
                        $text = '';
                        
                        // Convertir a HTML y extraer texto
                        $htmlWriter = WordIOFactory::createWriter($phpWord, 'HTML');
                        $tempHtml = tempnam(sys_get_temp_dir(), 'word_') . '.html';
                        $htmlWriter->save($tempHtml);
                        
                        $htmlContent = file_get_contents($tempHtml);
                        $text = strip_tags($htmlContent);
                        $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML401, 'UTF-8');
                        
                        unlink($tempHtml); // Limpiar archivo temporal
                        return $text;
                    } catch (\Exception $e) {
                        Log::error('Error procesando documento Word', ['error' => $e->getMessage()]);
                        return "Error al procesar documento Word: " . basename($filePath);
                    }
                    
                case 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet': // .xlsx
                case 'application/vnd.ms-excel': // .xls
                    $spreadsheet = SpreadsheetIOFactory::load($filePath);
                    $text = '';
                    foreach ($spreadsheet->getAllSheets() as $sheet) {
                        $text .= "Hoja: " . $sheet->getTitle() . "\n";
                        $highestRow = $sheet->getHighestRow();
                        $highestCol = $sheet->getHighestColumn();
                        
                        for ($row = 1; $row <= min($highestRow, 50); $row++) { // Limitar a 50 filas
                            $rowText = '';
                            for ($col = 'A'; $col <= $highestCol && $col <= 'J'; $col++) { // Limitar a columna J
                                $value = $sheet->getCell($col . $row)->getCalculatedValue();
                                if (!empty($value)) {
                                    $rowText .= $value . "\t";
                                }
                            }
                            if (!empty(trim($rowText))) {
                                $text .= $rowText . "\n";
                            }
                        }
                        $text .= "\n";
                    }
                    return $text;
                    
                default:
                    Log::warning('Tipo de archivo no soportado para extracción de texto', ['mime' => $mimeType]);
                    return "Archivo adjunto: " . basename($filePath);
            }
        } catch (\Exception $e) {
            Log::error('Error al extraer contenido del documento', ['error' => $e->getMessage()]);
            return "Error al leer el contenido del archivo: " . basename($filePath);
        }
    }

    private function formatBytes($size, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $size > 1024 && $i < count($units) - 1; $i++) {
            $size /= 1024;
        }
        
        return round($size, $precision) . ' ' . $units[$i];
    }

    // Método alternativo usando Laravel HTTP Client
    private function sendToOpenAIWithHttp($messages, $hasImages = false)
    {
        $apiKey = config('openai.api_key') ?? env('OPENAI_API_KEY');
        
        // Usar gpt-4o-mini para imágenes (más económico), gpt-3.5-turbo para texto
        $model = $hasImages ? 'gpt-4o-mini' : 'gpt-3.5-turbo';
        
        Log::info('Usando modelo OpenAI', [
            'model' => $model,
            'has_images' => $hasImages,
            'messages_count' => count($messages)
        ]);
        
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
            'Content-Type' => 'application/json',
        ])
        ->timeout(60)
        ->connectTimeout(30)
        ->withOptions([
            'verify' => false, // Deshabilitar verificación SSL para XAMPP
        ])
        ->post('https://api.openai.com/v1/chat/completions', [
            'model' => $model,
            'messages' => $messages,
            'max_tokens' => $hasImages ? 300 : 500,
            'temperature' => 0.7,
        ]);

        if ($response->failed()) {
            throw new \Exception('Error HTTP: ' . $response->status() . ' - ' . $response->body());
        }

        return $response->json();
    }

    public function sendMessage()
    {

        Log::info('Método sendMessage llamado', [
            'message' => $this->message,
            'images_count' => count($this->images ?? []),
            'documents_count' => count($this->documents ?? [])
        ]);
        
        // Limpiar errores previos
        $this->errorMessage = '';

        // Validar límites de archivos antes de continuar
        if (!empty($this->images) && count($this->images) > 5) {
            $this->errorMessage = 'No puedes subir más de 5 imágenes a la vez.';
            return;
        }

        if (!empty($this->documents) && count($this->documents) > 5) {
            $this->errorMessage = 'No puedes subir más de 5 documentos a la vez.';
            return;
        }

        // Verificar límite total de archivos (para evitar saturar el mensaje)
        $totalFiles = count($this->images ?? []) + count($this->documents ?? []);
        if ($totalFiles > 8) {
            $this->errorMessage = 'No puedes subir más de 8 archivos en total (imágenes + documentos).';
            return;
        }

        // Validar que hay contenido (mensaje, imágenes o documentos)
        $trimmedMessage = trim($this->message);
        
        Log::info('Validación de mensaje', [
            'message_raw' => $this->message,
            'message_trimmed' => $trimmedMessage,
            'message_length' => strlen($trimmedMessage),
            'has_images' => !empty($this->images),
            'has_documents' => !empty($this->documents)
        ]);
        
        if (empty($trimmedMessage) && empty($this->images) && empty($this->documents)) {
           // $this->errorMessage = 'Por favor, escribe un mensaje, selecciona una imagen o adjunta un documento antes de enviar.';
            return;
        }

        // Validar longitud del mensaje si existe
        if (!empty(trim($this->message)) && strlen(trim($this->message)) > 1000) {
            $this->errorMessage = 'El mensaje es demasiado largo. Máximo 1000 caracteres.';
            return;
        }

        // Validar imágenes y documentos
        if (!empty($this->images) || !empty($this->documents)) {
            $this->validate();
        }

        $this->isLoading = true;

        try {
            // Log de debugging
            Log::info('Iniciando envío de mensaje', [
                'user_id' => Auth::id(),
                'message_length' => strlen($this->message),
                'chatgroup_id' => $this->chatgroup_id
            ]);

            // Verificar que la API key esté configurada
            $apiKey = config('openai.api_key') ?? env('OPENAI_API_KEY');
            
            if (empty($apiKey)) {
                throw new \Exception('API key de OpenAI no encontrada en configuración');
            }

            // Guardar mensaje del usuario
            $userMessage = Chat::create([
                'message' => trim($this->message) ?: null,
                'emisor_id' => Auth::id(),
                'receiver' => 1, // ID del sistema/IA
                'chatgroup_id' => $this->chatgroup_id,
            ]);

            Log::info('Mensaje del usuario guardado', ['message_id' => $userMessage->id]);

            // Guardar imágenes si existen
            $imageContents = [];
            if (!empty($this->images)) {
                foreach ($this->images as $image) {
                    // Guardar archivo
                    $path = $image->store('chat-images', 'public');
                    
                    // Crear registro en la base de datos
                    $file = File::create([
                        'type' => 'image',
                        'name' => $image->getClientOriginalName(),
                        'path' => $path,
                        'mime_type' => $image->getMimeType(),
                        'size' => $image->getSize(),
                        'chat_id' => $userMessage->id,
                    ]);

                    // Preparar contenido para OpenAI Vision (convertir a base64)
                    $originalImageContent = $image->get();
                    $mimeType = $image->getMimeType();
                    
                    // Redimensionar imagen si es muy grande para OpenAI
                    $resizedImageContent = $this->resizeImageForOpenAI($originalImageContent, $mimeType);
                    $imageData = base64_encode($resizedImageContent);
                    
                    $imageContents[] = [
                        'type' => 'image_url',
                        'image_url' => [
                            'url' => "data:{$mimeType};base64,{$imageData}"
                        ]
                    ];

                    Log::info('Imagen guardada', [
                        'file_id' => $file->id,
                        'path' => $path,
                        'name' => $file->name
                    ]);
                }
            }

            // Guardar documentos si existen y extraer contenido
            $documentContents = [];
            if (!empty($this->documents)) {
                foreach ($this->documents as $document) {
                    // Guardar archivo
                    $path = $document->store('chat-documents', 'public');
                    
                    // Guardar en base de datos
                    $file = File::create([
                        'type' => 'document',
                        'name' => $document->getClientOriginalName(),
                        'path' => $path,
                        'mime_type' => $document->getMimeType(),
                        'size' => $document->getSize(),
                        'chat_id' => $userMessage->id,
                    ]);

                    // Extraer contenido del documento
                    $fullPath = storage_path('app/public/' . $path);
                    $content = $this->extractDocumentContent($fullPath, $document->getMimeType());
                    $documentContents[] = $content;

                    Log::info('Documento guardado y procesado', [
                        'file_id' => $file->id,
                        'path' => $path,
                        'name' => $file->name,
                        'content_length' => strlen($content)
                    ]);
                }
            }

            // Preparar el mensaje actual (incluyendo contenido de documentos)
            $currentMessage = trim($this->message) ?: '';
            
            // Si hay documentos, agregar su contenido al mensaje
            if (!empty($documentContents)) {
                if (empty($currentMessage)) {
                    $currentMessage = 'Analiza el siguiente documento:';
                } else {
                    $currentMessage .= "\n\nDocumentos adjuntos:";
                }
                
                foreach ($documentContents as $content) {
                    $currentMessage .= "\n\n---DOCUMENTO---\n" . substr($content, 0, 3000) . "\n---FIN DOCUMENTO---"; // Limitar a 3000 chars por doc
                }
            } elseif (!empty($this->images)) {
                $currentMessage = $currentMessage ?: 'Analiza esta imagen';
            }

            $hasImages = !empty($this->images);
            
            // Limpiar inputs
            $this->reset(['message', 'images', 'previewImages', 'documents', 'previewDocuments']);
            $this->loadMessages();

            // Preparar mensajes para OpenAI con contexto personalizado
            $userName = Auth::user()->name;
            $messages = [
                [
                    'role' => 'system',
                    'content' => $hasImages 
                        ? "Hola, soy tu asistente de inteligencia artificial especializado de la empresa y estoy aquí para ayudarte, {$userName}. 

Puedo analizar imágenes, documentos y responder cualquier consulta que tengas de manera profesional y cordial. Mi objetivo es brindarte el mejor soporte posible para que puedas realizar tu trabajo de manera eficiente.

Siempre me dirigiré a ti por tu nombre cuando sea apropiado, mantendré un tono profesional pero cercano, y me aseguraré de que te sientas cómodo/a consultando conmigo cualquier duda. 

¿En qué puedo ayudarte hoy, {$userName}?"

                        : "Hola, soy tu asistente de inteligencia artificial de la empresa y estoy aquí para apoyarte, {$userName}.

Puedo ayudarte con consultas, análisis de documentos, resolver dudas técnicas, y cualquier otra tarea que necesites para tu trabajo. Mi enfoque es siempre profesional, eficiente y orientado a resultados.

Me gusta mantener una conversación natural y cercana, pero siempre dentro del marco corporativo apropiado. No dudes en consultarme cualquier cosa que necesites.

¿Cómo puedo asistirte el día de hoy, {$userName}?"
                ]
            ];

            // Preparar contenido del mensaje del usuario
            if ($hasImages) {
                $userContent = [
                    ['type' => 'text', 'text' => $currentMessage]
                ];
                $userContent = array_merge($userContent, $imageContents);
                
                $messages[] = [
                    'role' => 'user',
                    'content' => $userContent
                ];
            } else {
                $messages[] = [
                    'role' => 'user',
                    'content' => $currentMessage
                ];
            }

            Log::info('Enviando request a OpenAI', [
                'message_preview' => substr($currentMessage, 0, 50) . '...',
                'has_images' => $hasImages,
                'images_count' => count($imageContents),
                'model_to_use' => $hasImages ? 'gpt-4o-mini' : 'gpt-3.5-turbo'
            ]);

            // Intentar primero con Laravel HTTP Client
            try {
                $response = $this->sendToOpenAIWithHttp($messages, $hasImages);
                
                if (!isset($response['choices'][0]['message']['content'])) {
                    throw new \Exception('Respuesta inválida de OpenAI: no se encontró contenido');
                }

                $aiResponse = $response['choices'][0]['message']['content'];
                
                Log::info('Respuesta recibida de OpenAI (HTTP)', [
                    'response_length' => strlen($aiResponse)
                ]);

            } catch (\Exception $httpException) {
                Log::warning('HTTP Client falló', [
                    'http_error' => $httpException->getMessage(),
                    'has_images' => $hasImages
                ]);

                // Si hay imágenes, no usar fallback porque el package puede no soportar el formato base64
                if ($hasImages) {
                    throw new \Exception('Error enviando imágenes a OpenAI: ' . $httpException->getMessage());
                }

                // Fallback solo para mensajes de texto: usar el package de OpenAI Laravel
                Log::info('Usando fallback con OpenAI package para mensaje de texto');
                $openaiResponse = OpenAI::chat()->create([
                    'model' => 'gpt-3.5-turbo',
                    'messages' => $messages,
                    'max_tokens' => 500,
                    'temperature' => 0.7,
                ]);

                if (empty($openaiResponse->choices) || !isset($openaiResponse->choices[0]->message->content)) {
                    throw new \Exception('Respuesta inválida de OpenAI: no se encontró contenido');
                }

                $aiResponse = $openaiResponse->choices[0]->message->content;
                
                Log::info('Respuesta recibida de OpenAI (Package)', [
                    'response_length' => strlen($aiResponse)
                ]);
            }

            // Guardar respuesta de la IA
            $aiMessage = Chat::create([
                'message' => trim($aiResponse),
                'emisor_id' => null, // null para que se muestre como IA
                'receiver' => Auth::id(),
                'chatgroup_id' => $this->chatgroup_id,
            ]);

            Log::info('Respuesta de IA guardada', ['ai_message_id' => $aiMessage->id]);

        } catch (\OpenAI\Exceptions\ErrorException $e) {
            // Error específico de OpenAI API
            Log::error('Error de OpenAI API', [
                'error' => $e->getMessage(),
                'code' => $e->getCode(),
                'user_id' => Auth::id()
            ]);

            $errorMsg = 'Error de OpenAI API: ' . $e->getMessage();
            $this->errorMessage = $errorMsg;

            Chat::create([
                'message' => $errorMsg,
                'emisor_id' => null, // null para que se muestre como IA
                'receiver' => Auth::id(),
                'chatgroup_id' => $this->chatgroup_id,
            ]);

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            // Error de conexión HTTP
            Log::error('Error de conexión HTTP', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id()
            ]);

            $errorMsg = 'Error de conexión. Verifica tu conexión a internet e inténtalo nuevamente.';
            $this->errorMessage = $errorMsg;

            Chat::create([
                'message' => $errorMsg,
                'emisor_id' => null, // null para que se muestre como IA
                'receiver' => Auth::id(),
                'chatgroup_id' => $this->chatgroup_id,
            ]);

        } catch (\Exception $e) {
            // Error general
            Log::error('Error general en sendMessage', [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
                'trace' => substr($e->getTraceAsString(), 0, 500),
                'user_id' => Auth::id()
            ]);

            $errorMsg = 'Error: ' . $e->getMessage();
            $this->errorMessage = $errorMsg;

            Chat::create([
                'message' => $errorMsg,
                'emisor_id' => null, // null para que se muestre como IA
                'receiver' => Auth::id(),
                'chatgroup_id' => $this->chatgroup_id,
            ]);
        }
        $this->message = '';
        $this->isLoading = false;

        $this->loadMessages();
    }

    public function testOpenAI()
    {
        try {
            Log::info('Testeando conexión OpenAI con HTTP Client');
            
            $userName = Auth::user()->name;
            $messages = [
                [
                    'role' => 'system', 
                    'content' => "Soy tu asistente de IA corporativo. Respondo de manera profesional y cercana, dirigiéndome al usuario por su nombre cuando sea apropiado."
                ],
                [
                    'role' => 'user', 
                    'content' => 'Haz una prueba de conexión rápida'
                ]
            ];

            // Probar con HTTP Client primero
            try {
                $response = $this->sendToOpenAIWithHttp($messages);
                $result = $response['choices'][0]['message']['content'];
                $this->errorMessage = 'Test HTTP exitoso: ' . $result;
                Log::info('Test HTTP exitoso', ['response' => $result]);
            } catch (\Exception $httpException) {
                Log::info('HTTP falló, probando con OpenAI package');
                
                // Fallback con OpenAI package
                $response = OpenAI::chat()->create([
                    'model' => 'gpt-3.5-turbo',
                    'messages' => $messages,
                    'max_tokens' => 10
                ]);

                $result = $response->choices[0]->message->content;
                $this->errorMessage = 'Test Package exitoso: ' . $result;
                Log::info('Test Package exitoso', ['response' => $result]);
            }
            
        } catch (\Exception $e) {
            $errorMsg = 'Test falló: ' . $e->getMessage();
            $this->errorMessage = $errorMsg;
            Log::error('Test falló', [
                'error' => $e->getMessage(),
                'line' => $e->getLine()
            ]);
        }
    }

    public function clearChat()
    {
        Chat::where('chatgroup_id', $this->chatgroup_id)->delete();
        $this->loadMessages();
        $this->errorMessage = '';
        $this->dispatch('chatCleared');
    }

    public function render()
    {
        return view('livewire.chat.chat-index');
    }
}