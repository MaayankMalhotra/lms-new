<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Http; 
use Illuminate\Http\Request;

class ChatbotController extends Controller
{
public function index()
{
    return view('chatbot');
}

 public function send_with_no_context(Request $request)
    {
        $userMessage = $request->input('message');
        

        // Call Ollama API
        $response = Http::post('http://127.0.0.1:11434/api/chat', [
            'model' => 'phi:2.7bo',
            'messages' => [
                ['role' => 'user', 'content' => $userMessage],
            ],
            'stream' => false,
        ]);

        return response()->json([
            'reply' => $response->json()['message']['content'] ?? 'No response',
        ]);
    }


public function send(Request $request)
    {
        $userMessage = (string) $request->input('message');

        // ---- Resume Context ----
        $resumeContext = <<<EOT
        Mayank Malhotra
        Software Engineer

        CONTACT
        +91 8799730966
        maayankmalhotra095@gmail.com
        http://bit.ly/46IAGEv
        https://github.com/MaayankMalhotra
        Portfolio Website link

        SKILLS
        Frontend: HTML5, CSS3, JavaScript, TypeScript, React.js, Redux
        Backend: Node.js, Express.js, PHP, Laravel
        APIs & Databases: RESTful APIs, Apollo GraphQL, MySQL, MongoDB
        Tools & DevOps: Docker, AWS, Git, BitBucket, Linux, MacOS

        EXPERIENCE
        - Thinktail Global Pvt. Ltd. | Software Engineer | Aug 2025 – Present
          Led full-stack development for scalable web apps using React.js and Node.js.
          Mentored junior developers and ensured timely delivery.

        - Cracode Consulting Pvt. Ltd. | Software Engineer | Aug 2024 – Aug 2025
          Built Laravel + React.js apps for clients, deployed APIs handling 1.5M+ transactions/month.

        - Henry Harvin | Software Engineer | Oct 2023 – Aug 2024
          Boosted platform performance by 20%, delivered 5 product releases, and improved CI/CD.

        - SSNTPL | Software Engineer | Jan 2023 – Oct 2023
          Developed REST APIs and backend systems for ICICI Lombard and Ninja CRM.

        EDUCATION
        - B.Tech, Electronics – YMCA University | 2018–2022 | CGPA: 7.606
        - Senior Secondary – D.A.V. Public School | 2017–2018 | 74%
        - Secondary – D.A.V. Public School | 2015–2016 | CGPA: 8.6

        PROJECTS
        - Job Portal (MERN): Built job listing portal with filters, resume upload, and admin panel. Deployed on AWS.
        - LMS Portal (Laravel): Designed LMS with real-time updates using Pusher.
        - CRM App (MERN): Lead tracking, pipeline management, task automation, dashboards.
        - Audio/Video App (PHP, Node.js): Real-time communication with WebRTC & socket.io.
        EOT;

        try {
            $resp = Http::withHeaders([
                'Content-Type'   => 'application/json',
                'x-goog-api-key' => 'AIzaSyAt_bJBocLkutUwk8L-zkXAN9FQmWif6Fw',
            ])->post(
                'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent',
                [
                    'contents' => [
                        [
                            'role' => 'user',
                            'parts' => [[
                                'text' => "Here is the resume context:\n\n$resumeContext\n\nNow answer this question: $userMessage"
                            ]],
                        ],
                    ],
                ]
            );

            if ($resp->failed()) {
                Log::error('Gemini API error', ['status' => $resp->status(), 'body' => $resp->body()]);
                return response()->json([
                    'reply' => 'Sorry, abhi response nahi mil paya.',
                    'error' => $resp->json(),
                ], $resp->status());
            }

            $data  = $resp->json();
            $reply = data_get($data, 'candidates.0.content.parts.0.text', 'No response');

            return response()->json(['reply' => $reply]);
        } catch (\Throwable $e) {
            Log::error('Gemini API exception', ['message' => $e->getMessage()]);
            return response()->json([
                'reply'  => 'Kuch galat ho gaya.',
                'error'  => 'exception',
                'detail' => $e->getMessage(),
            ], 500);
        }
    }


public function send_phi_model(Request $request)
{
    $userMessage = $request->input('message');

    // Static context (identity + resume summary)
    $context = "
You are Maayank Malhotra's personal assistant. 
Always introduce yourself as his assistant and respond as if you represent him. 
Answer questions using only the following context (resume data):

Name: Maayank Malhotra
Role: Software Engineer

📞 Contact:
- Phone: +91 8799730966
- Email: maayankmalhotra095@gmail.com
- Portfolio: http://bit.ly/46IAGEv
- GitHub: https://github.com/MaayankMalhotra

🛠 Skills:
- Frontend: HTML5, CSS3, JavaScript, TypeScript, React.js, Redux
- Backend: Node.js, Express.js, PHP, Laravel
- APIs & Databases: REST APIs, Apollo GraphQL, MySQL, MongoDB
- Tools & DevOps: Docker, AWS, Git, BitBucket, Linux, macOS

💼 Experience:
- Thinktail Global Pvt. Ltd. (Aug 2025 – Present): Full-stack development with React.js & Node.js, analytics & automation, mentoring juniors.
- Cracode Consulting Pvt. Ltd. (Aug 2024 – Aug 2025): Built Laravel + React apps, APIs with 1.5M+ transactions/month, reusable UI components.
- Henry Harvin (Oct 2023 – Aug 2024): Improved platform performance by 20%, delivered 5 product releases, optimized CI/CD.
- SSNTPL (Jan 2023 – Oct 2023): Developed REST APIs for ICICI Lombard & Ninja CRM, improved engagement & reliability.

🎓 Education:
- B.Tech Electronics, YMCA University (2018–2022) – CGPA: 7.606
- Senior Secondary – D.A.V. Public School (2017–2018) – 74%
- Secondary – D.A.V. Public School (2015–2016) – CGPA: 8.6

📂 Projects:
- Job Portal (MERN): Job listings, resume upload, search filters, AWS deployment.
- LMS Portal (Laravel): Student/teacher roles, assignments, grading, real-time updates.
- CRM App (MERN): Lead tracking, pipeline mgmt, task automation, dashboards, email integration.
- Audio/Video App (PHP, Node.js): Real-time WebRTC platform with recording, chat, multi-browser support.

Guideline: 
- Always act as his personal AI assistant.
- Answer concisely, professionally, and stay relevant to the resume context.
";

    // Call Ollama API
    $response = Http::post('http://127.0.0.1:11434/api/chat', [
        'model' => 'phi:2.7b',
        'messages' => [
            ['role' => 'system', 'content' => $context],   // Injected context
            ['role' => 'user', 'content' => $userMessage], // User's query
        ],
        'stream' => false,
    ]);

    return response()->json([
        'reply' => $response->json()['message']['content'] ?? 'No response',
    ]);
}



}
