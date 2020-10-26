<?php

namespace Modules\Infrastructure\Utilities\Interfaces;

interface FileUploaderInterface
{
    public function uploadBase64File(string $base64File, string $prefixName = '', $driver = '', array $directory = []) : string;

    public function upload(string $fileContent, string $fileName, $driver = 's3_public', array $directory = []): string;

    public function destroy(string $path, $driver = 's3'): bool;
    public function download(string $path, $driver = 's3', $file_alias = '');
}
