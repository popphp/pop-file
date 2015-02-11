<?php
/**
 * Pop PHP Framework (http://www.popphp.org/)
 *
 * @link       https://github.com/popphp/popphp
 * @category   Pop
 * @package    Pop_File
 * @author     Nick Sagona, III <dev@nolainteractive.com>
 * @copyright  Copyright (c) 2009-2015 NOLA Interactive, LLC. (http://www.nolainteractive.com)
 * @license    http://www.popphp.org/license     New BSD License
 */

/**
 * @namespace
 */
namespace Pop\File;

/**
 * File class
 *
 * @category   Pop
 * @package    Pop_File
 * @author     Nick Sagona, III <dev@nolainteractive.com>
 * @copyright  Copyright (c) 2009-2015 NOLA Interactive, LLC. (http://www.nolainteractive.com)
 * @license    http://www.popphp.org/license     New BSD License
 * @version    2.0.0a
 */
class Upload
{

    /**
     * The upload directory path
     * @var string
     */
    protected $uploadDir = null;

    /**
     * The final filename of the uploaded file
     * @var string
     */
    protected $uploadedFile = null;

    /**
     * Allowed maximum file size
     * @var int
     */
    protected $maxSize = 0;

    /**
     * Allowed file types
     * @var array
     */
    protected $allowedTypes = [];

    /**
     * Overwrite flag
     * @var boolean
     */
    protected $overwrite = false;

    /**
     * Success flag
     * @var boolean
     */
    protected $success = false;

    /**
     * Constructor
     *
     * Instantiate a file upload object
     *
     * @param  string $dir
     * @param  int    $maxSize
     * @param  array  $allowedTypes
     * @return Upload
     */
    public function __construct($dir, $maxSize = 0, array $allowedTypes = null)
    {
        $this->setUploadDir($dir);
        $this->setMaxSize($maxSize);

        if ((null !== $allowedTypes) && (count($allowedTypes) > 0)) {
            $this->setAllowedTypes($allowedTypes);
        }
    }

    /**
     * Use default file upload settings
     *
     * @param  int $maxSize
     * @return Upload
     */
    public function useDefaults($maxSize = 10000000)
    {
        $this->maxSize      = (int)$maxSize;
        $this->allowedTypes = [
            'ai', 'aif', 'aiff', 'avi', 'bmp', 'bz2', 'csv', 'doc', 'docx', 'eps', 'fla', 'flv', 'gif', 'gz',
            'jpe','jpg', 'jpeg', 'json', 'log', 'md', 'mov', 'mp2', 'mp3', 'mp4', 'mpg', 'mpeg', 'otf', 'pdf',
            'png', 'ppt', 'pptx', 'psd', 'rar', 'sql', 'sqlite', 'svg', 'swf', 'tar', 'tbz', 'tbz2', 'tgz',
            'tif', 'tiff', 'tsv', 'ttf', 'txt', 'wav', 'wma', 'wmv', 'xls', 'xlsx', 'xml', 'yaml', 'yml', 'zip'
        ];
        return $this;
    }

    /**
     * Set the upload directory
     *
     * @param  string $dir
     * @throws Exception
     * @return Upload
     */
    public function setUploadDir($dir)
    {
        // Check to see if the upload directory exists.
        if (!file_exists($dir)) {
            throw new Exception('Error: The upload directory does not exist.');
        }

        // Check to see if the permissions are set correctly.
        if (!is_writable(dirname($dir))) {
            throw new Exception('Error: The upload directory is not writable.');
        }

        $this->uploadDir = $dir;
        return $this;
    }

    /**
     * Set the upload directory
     *
     * @param  int $maxSize
     * @return Upload
     */
    public function setMaxSize($maxSize)
    {
        $this->maxSize = (int)$maxSize;
        return $this;
    }

    /**
     * Set the allowed types
     *
     * @param  array $allowedTypes
     * @return Upload
     */
    public function setAllowedTypes(array $allowedTypes)
    {
        $this->allowedTypes = $allowedTypes;
        return $this;
    }

    /**
     * Add an allowed type
     *
     * @param  string $type
     * @return Upload
     */
    public function addAllowedType($type)
    {
        if (!in_array($type, $this->allowedTypes)) {
            $this->allowedTypes[] = $type;
        }
        return $this;
    }

    /**
     * Remove an allowed type
     *
     * @param  string $type
     * @return Upload
     */
    public function removeAllowedType($type)
    {
        if (in_array($type, $this->allowedTypes)) {
            unset($this->allowedTypes[array_search($type, $this->allowedTypes)]);
        }
        return $this;
    }

    /**
     * Set the overwrite flag
     *
     * @param  boolean $overwrite
     * @return Upload
     */
    public function overwrite($overwrite)
    {
        $this->overwrite = (bool)$overwrite;
        return $this;
    }

    /**
     * Get the upload directory
     *
     * @return string
     */
    public function getUploadDir()
    {
        return $this->uploadDir;
    }

    /**
     * Get uploaded file
     *
     * @return string
     */
    public function getUploadedFile()
    {
        return $this->uploadedFile;
    }

    /**
     * Get uploaded file full path
     *
     * @return string
     */
    public function getUploadedFullPath()
    {
        return $this->uploadDir . DIRECTORY_SEPARATOR . $this->uploadedFile;
    }

    /**
     * Get the allowed max size
     *
     * @return int
     */
    public function getMaxSize()
    {
        return $this->maxSize;
    }

    /**
     * Get the allowed file types
     *
     * @return array
     */
    public function getAllowedTypes()
    {
        return $this->allowedTypes;
    }

    /**
     * Determine if a file type is allowed
     *
     * @param  string $ext
     * @return boolean
     */
    public function isAllowed($ext)
    {
        return ((count($this->allowedTypes) == 0) ||
            ((count($this->allowedTypes) > 0) && (in_array(strtolower($ext), $this->allowedTypes))));
    }

    /**
     * Determine if the overwrite flag is set
     *
     * @return boolean
     */
    public function isOverwrite()
    {
        return $this->overwrite;
    }

    /**
     * Determine if the upload was a success
     *
     * @return boolean
     */
    public function isSuccess()
    {
        return $this->success;
    }

    /**
     * Check filename for duplicates
     *
     * @param  string $file
     * @return string
     */
    public function checkFilename($file)
    {
        $newFilename  = $file;
        $parts        = pathinfo($file);
        $origFilename = $parts['filename'];
        $ext          = (isset($parts['extension']) && ($parts['extension'] != '')) ? '.' . $parts['extension'] : null;

        $i = 1;

        while (file_exists($this->uploadDir . DIRECTORY_SEPARATOR . $newFilename)) {
            $newFilename = $origFilename . '_' . $i . $ext;
            $i++;
        }

        return $newFilename;
    }

    /**
     * Upload file to the upload dir, returns full path of the newly uploaded file
     *
     * @param  string $src
     * @param  string $dest
     * @throws Exception
     * @return string
     */
    public function upload($src, $dest)
    {
        if (!$this->overwrite) {
            $dest = $this->checkFilename($dest);
        }

        $this->uploadedFile = $dest;
        $dest = $this->uploadDir . DIRECTORY_SEPARATOR . $dest;

        // Move the uploaded file, creating a file object with it.
        if (move_uploaded_file($src, $dest)) {
            $fileSize  = filesize($dest);
            $fileParts = pathinfo($dest);

            $ext = (isset($fileParts['extension'])) ? $fileParts['extension'] : null;

            // Check the file size requirement.
            if (($this->maxSize > 0) && ($fileSize > $this->maxSize)) {
                unlink($dest);
                throw new Exception('Error: The file uploaded is too big.');
            }

            // Check to see if the file is an accepted file format.
            if ((null !== $ext) && (!$this->isAllowed($ext))) {
                unlink($dest);
                throw new Exception('Error: The file type ' . strtoupper($ext) . ' is not an accepted file format.');
            }

            $this->success = true;
            return $this->uploadedFile;
        } else {
            throw new Exception('Error: There was an unexpected error in uploading the file.');
        }
    }

}
