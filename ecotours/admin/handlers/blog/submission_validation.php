<?php
function validateBlogSubmission() {
    if (empty($_POST) && !empty($_SERVER['CONTENT_LENGTH'])) {
        throw new Exception('The submission exceeded the server request limit (' . ini_get('post_max_size') . '). Please reduce the image sizes and retry.');
    }
    $fieldCount = 0;
    $countFields = function ($value) use (&$countFields, &$fieldCount) {
        if (is_array($value)) foreach ($value as $item) $countFields($item);
        else $fieldCount++;
    };
    foreach ($_POST as $key => $value) {
        if ($key !== '_submission_manifest') $countFields($value);
    }
    $fileCount = 0;
    foreach ($_FILES as $field => $upload) {
        $errors = is_array($upload['error']) ? $upload['error'] : [$upload['error']];
        foreach ($errors as $error) {
            if ($error === UPLOAD_ERR_NO_FILE) continue;
            if ($error !== UPLOAD_ERR_OK) {
                $reason = in_array($error, [UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE], true)
                    ? 'exceeds the server upload limit (' . ini_get('upload_max_filesize') . ')'
                    : 'did not finish uploading (error ' . $error . ')';
                throw new Exception('Image ' . $field . ' ' . $reason . '. No blog changes were saved.');
            }
            $fileCount++;
        }
    }
    // Older deployed clients remain compatible; new clients require the final marker.
    if (isset($_POST['_submission_manifest'])) {
        $manifest = json_decode($_POST['_submission_manifest'], true);
        if (!is_array($manifest) || ($manifest['fields'] ?? -1) !== $fieldCount || ($manifest['files'] ?? -1) !== $fileCount) {
            throw new Exception('The server received an incomplete submission. No blog changes were saved. Reduce the image count or sizes and retry.');
        }
    } elseif (isset($_POST['_submission_version'])) {
        throw new Exception('The server truncated the submission. No blog changes were saved. Ask your host to increase max_input_vars.');
    }
}
