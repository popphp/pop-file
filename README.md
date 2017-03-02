pop-file
========

END OF LIFE
-----------
The `pop-file` component v2.1.0 is now end-of-life. The upload and dir sub-components have
been forked and pushed into other repositories, respectively:

* popphp/pop-http (now the Pop\Http\Upload class)
* popphp/pop-dir (now the Pop\Dir\Dir class)

[![Build Status](https://travis-ci.org/popphp/pop-file.svg?branch=master)](https://travis-ci.org/popphp/pop-file)
[![Coverage Status](http://cc.popphp.org/coverage.php?comp=pop-file)](http://cc.popphp.org/pop-file/)

OVERVIEW
--------
`pop-file` is a component for managing file uploads and easily traversing files
within a directory. With file uploads, you can set fine-grain controls to
manage specific upload parameters like file types and file size. Also, you can
traverse directories recursively as well.

`pop-file`is a component of the [Pop PHP Framework](http://www.popphp.org/).

INSTALL
-------

Install `pop-file` using Composer.

    composer require popphp/pop-file

BASIC USAGE
-----------

### File uploads

##### Basic file upload

```php
use Pop\File\Upload;

$upload = new Upload('/path/to/uploads');
$upload->useDefaults();

$upload->upload($_FILES['file_upload']);

// Do something with the newly uploaded file
if ($upload->isSuccess()) {
    $file = $upload->getUploadedFile();
} else {
    echo $upload->getErrorMessage();
}
```

The above code creates the upload object, sets the upload path and sets the basic defaults,
which includes a max file size of 10MBs, and an array of allowed common file types as well
as an array of common disallowed file types.

##### File upload names and overwrites

By default, the file upload object will not overwrite a file of the same name. In the above
example, if `$_FILES['file_upload']['name']` is set to 'my_document.docx' and that file
already exists in the upload path, it will be renamed to 'my_document_1.docx'.

If you want to enable file overwrites, you can simply do this:

```php
$upload->overwrite(true);
```

Also, you can give the file a direct name on upload like this:

```php
$upload->upload($_FILES['file_upload'], 'my-custom-filename.docx');
```

And if you need to check for a duplicate filename first, you can use the `checkFilename`
method. If the filename exists, it will append a '\_1' to the end of the filename, or loop
through until it finds a number that doesn't exist yet (\_#). If the filename doesn't
exist yet, it returns the original name.

```php
$filename = $upload->checkFilename('my-custom-filename.docx');

// $filename is set to 'my-custom-filename_1.docx'
$upload->upload($_FILES['file_upload'], $filename);
```

### Directory traversal

##### Traversing a directory

```php
use Pop\File\Dir;

$dir = new Dir('my-dir');

foreach ($dir->getFiles() as $file) {
    echo $file;
}
```

If you want to traverse the directory recursively and get the full path of each file.

```php
use Pop\File\Dir;

$dir = new Dir('my-dir', [
    'absolute'  => true,
    'recursive' => true
]);

foreach ($dir->getFiles() as $file) {
    echo $file;
}
```

The available boolean options for the `$options` array parameter are:

* 'absolute'  => store the absolute, full path of the items in the directory
* 'relative'  => store the relative path of the items in the directory
* 'recursive' => traverse the directory recursively
* 'filesOnly' => store only files in the object (and not other directories)

##### Emptying a directory

```php
use Pop\File\Dir;

$dir = new Dir('my-dir');
$dir->emptyDir(true);
```

The `true` flag will remove the actually directory as well.

