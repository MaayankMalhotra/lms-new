<?php

namespace App\Services;

use Illuminate\Support\Str;
use Symfony\Component\Process\Process;

class CodeExecutor
{
    private string $basePath;

    public function __construct()
    {
        $this->basePath = storage_path('app/code_runs');

        if (!is_dir($this->basePath)) {
            mkdir($this->basePath, 0775, true);
        }
    }

    /**
     * Execute code for the given language, returning output and basic error flags.
     *
     * @return array{output:string,is_error:bool,exit_code:int,stage:string}
     */
    public function run(string $language, string $code, string $input = '', int $timeout = 5): array
    {
        $workspace = $this->makeWorkspace();
        $lang = strtolower($language);
        $result = [
            'output' => 'Invalid language requested.',
            'is_error' => true,
            'exit_code' => 1,
            'stage' => 'validation',
        ];

        try {
            switch ($lang) {
                case 'python':
                    $result = $this->runPython($workspace, $code, $input, $timeout);
                    break;
                case 'c':
                    $result = $this->runC($workspace, $code, $input, $timeout);
                    break;
                case 'c++':
                case 'cpp':
                    $result = $this->runCpp($workspace, $code, $input, $timeout);
                    break;
                case 'java':
                    $result = $this->runJava($workspace, $code, $input, $timeout);
                    break;
                default:
                    break;
            }
        } finally {
            $this->cleanup($workspace);
        }

        return $result;
    }

    private function runPython(string $workspace, string $code, string $input, int $timeout): array
    {
        $file = $workspace . '/main.py';
        file_put_contents($file, $code);

        $process = new Process(['python3', $file]);
        $process->setWorkingDirectory($workspace);
        $process->setInput($input);
        $process->setTimeout($timeout);
        $process->run();

        return $this->formatResult($process, 'run');
    }

    private function runC(string $workspace, string $code, string $input, int $timeout): array
    {
        $source = $workspace . '/main.c';
        $binary = $workspace . '/app';
        file_put_contents($source, $code);

        $compile = new Process(['gcc', $source, '-o', $binary]);
        $compile->setTimeout($timeout);
        $compile->run();

        if (!$compile->isSuccessful()) {
            return $this->formatResult($compile, 'compile');
        }

        $run = new Process([$binary]);
        $run->setWorkingDirectory($workspace);
        $run->setInput($input);
        $run->setTimeout($timeout);
        $run->run();

        return $this->formatResult($run, 'run');
    }

    private function runCpp(string $workspace, string $code, string $input, int $timeout): array
    {
        $source = $workspace . '/main.cpp';
        $binary = $workspace . '/app';
        file_put_contents($source, $code);

        $compile = new Process(['g++', $source, '-std=c++17', '-o', $binary]);
        $compile->setTimeout($timeout);
        $compile->run();

        if (!$compile->isSuccessful()) {
            return $this->formatResult($compile, 'compile');
        }

        $run = new Process([$binary]);
        $run->setWorkingDirectory($workspace);
        $run->setInput($input);
        $run->setTimeout($timeout);
        $run->run();

        return $this->formatResult($run, 'run');
    }

    private function runJava(string $workspace, string $code, string $input, int $timeout): array
    {
        $source = $workspace . '/Main.java';
        file_put_contents($source, $code);

        $compile = new Process(['javac', 'Main.java']);
        $compile->setWorkingDirectory($workspace);
        $compile->setTimeout($timeout);
        $compile->run();

        if (!$compile->isSuccessful()) {
            return $this->formatResult($compile, 'compile');
        }

        $run = new Process(['java', 'Main']);
        $run->setWorkingDirectory($workspace);
        $run->setInput($input);
        $run->setTimeout($timeout);
        $run->run();

        return $this->formatResult($run, 'run');
    }

    private function makeWorkspace(): string
    {
        $dir = $this->basePath . '/' . Str::uuid()->toString();
        mkdir($dir, 0775, true);
        return $dir;
    }

    private function cleanup(string $workspace): void
    {
        if (!is_dir($workspace)) {
            return;
        }

        foreach (glob($workspace . '/*') as $file) {
            @unlink($file);
        }

        @rmdir($workspace);
    }

    /**
     * Normalize process output into a consistent response payload.
     */
    private function formatResult(Process $process, string $stage): array
    {
        $output = trim($process->getOutput() . $process->getErrorOutput());

        return [
            'output' => $output !== '' ? $output : 'No output.',
            'is_error' => !$process->isSuccessful(),
            'exit_code' => $process->getExitCode() ?? 1,
            'stage' => $stage,
        ];
    }
}
