pop-file
========

[![Build Status](https://travis-ci.org/popphp/pop-file.svg?branch=master)](https://travis-ci.org/popphp/pop-file)
[![Coverage Status](http://www.popphp.org/cc/coverage.php?comp=pop-file)](http://www.popphp.org/cc/pop-file/)

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

$upload->upload($_FILES['file_upload'])

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

##### File upload overwrites

By default, the file upload object will not overwrite a file of the same name. In the above
example, if `$_FILES['file_upload']['name']` is set to 'my_document.docx' and that file
already exists in the upload path, it will be renamed to 'my_document_1.docx'.

If you want to enable file overwrites, you can simply do this:

```php
$upload->overwrite(true);
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

$dir = new Dir('my-dir', true, true, false);

foreach ($dir->getFiles() as $file) {
    echo $file;
}
```

The four parameters above are:

* The directory
* Full path flag
* Recursive flag
* Flag to not include other directories

##### Emptying a directory

```php
use Pop\File\Dir;

$dir = new Dir('my-dir');
$dir->emptyDir(true);
```

The `true` flag will remove the actually directory as well.

