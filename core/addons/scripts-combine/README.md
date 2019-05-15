# GtkScriptsCombine

> The **GtkScriptsCombine** class provides an easy way to load and combine (and minify using the JShrink class) 
multiple scripts or stylesheets into a single file that will be automatically included in the page header/footer of 
your website. This class provides a great way for theme developers, and not only, to limit the number of http requests
 in order to load a bunch of scripts and stylesheets required by a theme.

## Information

<table>
  <tr>
    <td>Class</td>
    <td>GtkScriptsCombine</td>
  </tr>
  <tr>
    <td>Version</td>
    <td>1.0.0</td>
  </tr>
  <tr>
    <td>WordPress Version</td>
    <td>>= 4.1</td>
  </tr>
  <tr>
    <td>PHP</td>
    <td>>= 5.4.0</td>
  </tr>
</table>


### Usage

```
    /*
     * Instantiate the class
     */
    $wpksc = new GtkScriptsCombine();
```

Afterwards, you can use the instance of the class, now stored in the **$wpksc** variable to combine and enqueue scripts:

```
@file header.php

    //#! Clear cache first
    $wpksc->clearCache();

    // JavaScript
    $wpksc->combineJS(array(
        // These paths MUST be relative to baseDir path specified below
        'res/b/b.js',
        'res/c/c.js',
        'res/a.js',
    ), array('jquery'), trailingslashit(get_template_directory()), false);

    /*
     * The above configuration would match a directory tree like this:
     *
     * [theme-dir] => retrieved using: trailingslashit(get_template_directory())
     *      [res]
     *          a.js
     *          [b]
     *              b.js
     *          [c]
     *              c.js
     */

    wp_head();
```

To combine stylesheets:

```
@file header.php

    //#! Clear cache first
    $wpksc->clearCache();

    // Stylesheets
    $wpksc->combineCSS(array(
        // These paths MUST be relative to baseDir path specified below
        'res/b/b.css',
        'res/c/c.css',
        'res/a.css',
    ), array(), trailingslashit(get_template_directory()));

    /*
     * The above configuration would match a directory tree like this:
     *
     * [theme-dir] => retrieved using: trailingslashit(get_template_directory())
     *      [res]
     *          a.css
     *          [b]
     *              b.css
     *          [c]
     *              c.css
     */

    wp_head();
```

## Methods

### **GtkScriptsCombine::__construct( $minify = true )**
<table>
  <tr>
    <td>$minify</td>
    <td>Whether or not to minify the output</td>
    <td>Default: true</td>
  </tr>
</table>

For scripts compression, I have used the minifier script **JShrink** provided by [tedious](https://github
.com/tedious/JShrink).

If the PHP version is less than 5.4 then the minify process will not run, regardless of the value provided in the class constructor.



### **GtkScriptsCombine::combineJS(array $files, $id, array $dependencies = array(), $baseDirPath = null, $inFooter = false)**
<table>
  <tr>
    <td>$files</td>
    <td>The list of files to combine</td>
  </tr>
  <tr>
    <td>$id</td>
    <td>The script ID</td>
  </tr>
  <tr>
    <td>$dependencies</td>
    <td>The list of dependencies. Ex: array('jquery', 'jquery-ui');</td>
  </tr>
  <tr>
    <td>$baseDirPath</td>
    <td>The base directory path where to search for scripts</td>
  </tr>
  <tr>
    <td>$inFooter</td>
    <td>Whether or not to include the output file in the page footer. Defaults to false.</td>
  </tr>
</table>

### **GtkScriptsCombine::combineCSS(array $files, $id, array $dependencies = array(), $baseDirPath = null)**
<table>
  <tr>
    <td>$files</td>
    <td>The list of files to combine</td>
  </tr>
  <tr>
    <td>$id</td>
    <td>The script ID</td>
  </tr>
  <tr>
    <td>$dependencies</td>
    <td>The list of dependencies. Ex: array('layout_css', 'template_css');</td>
  </tr>
  <tr>
    <td>$baseDirPath</td>
    <td>The base directory path where to search for stylesheets.</td>
  </tr>
</table>

### **GtkScriptsCombine::clearCache()**
Use this method to delete all cached files from the **cache** directory.

