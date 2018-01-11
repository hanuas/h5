//
// Lingua - A jQuery localization plugin
// Written by Jon McGuire, Copyright May 2011
// http://www.mindmagma.com/lingua
//
// MIT License applies (http://dev.jquery.com/browser/trunk/jquery/MIT-LICENSE.txt)
//
// Loosely based on concepts found in Acatl Pacheco's ResourceBundle plugin and
// Keith Wood's Localisation plugin.
//
// In short, the plugin reads name/value-pair text files based on a language code.
// These are retrieved on-the-fly via AJAX and are parsed into an associative array
// for quick and easy programmatic usage. (The methods are defined in a way which
// is customarily described as "polluting the namespace" but it's much cleaner in
// actual usage and requires less code.)
//
// The files are simple plain text files. Refer to the init method below for details
// of the file naming convention. CRITICAL: You must create these text files with a
// Unicode-aware text editor. Typically a UTF-8 plain-text file will contain the
// following "signature" as the first three bytes: Ôªø  ... Encoding issues are beyond
// the scope of these comments, but Visual Studio users can cut-and-paste those three
// bytes into a text file, then open it and safely edit the contents in Unicode (the
// mark will not be visible in the VS editor). Look up "Byte Order Mark" on Wikipedia
// for a good summary.
//
// Each name-value pair is simply a one-word name, a TAB, and a CRLF-terminated string
// that represents the value. (Actually the tab can be one or more whitespace characters
// which are stripped, and the value is inserted with the jQuery html() property so
// internal HTML is possible. This also means the translated content must be reviewed.)
//
// Blank lines and //-prefixed comment lines are ignored.
//
// #-prefixed keys are treated as element IDs for automated processing and are stored
// in a separate array after processing.
//
// The French demo file looks like this:
//
// ---------------------------------------------------------------------------
//      Ôªø
//      // fr: generic French
//      mycategory	generic French
//      mytitle		DÈmonstration de Localisation
//      mylabel		Choisir un code de langue
//
//      #divtest	Il s'agit d'un DIV automatiquement mis ‡ jour.
// ---------------------------------------------------------------------------
//
// Values are retrieved by calling the lingua plugin with the key name.
//
// Note that the jQuery standard for specifying a method name as the first
// parameter means that these method names MUST NOT appear as keys in your
// language file. It's kind of ugly, but JavaScript is one giant hack on top
// of another anyway.
//
// You'd write code like this:
//
//   $.linguaLoad('fr');
//   $("#title").html($.lingua("mytitle"));
//

(function ($) {

    // Technically these should be scoped into a data() pseudo-namespace
    var lingua__path;
    var lingua__name;
    var lingua__lang;
    var lingua__data = {};
    var lingua__eids = {};

    // string = $.lingua(key)
    // Returns the translated value for a given key.
    $.lingua = function (key) {
        if (lingua__data[key]) return lingua__data[key];
    }

    // $.linguaInit(path, name)
    // Initializes the lingua plugin with the path to the language files
    // and the base filename. No return value, but the getLang method will
    // be primed with the browser's default language, which could be used with
    // the load method.
    //
    // path
    // This is a simple path, such as /mysite/language/ ... For now, the plugin
    // assumes the formatting is correct and there is a trailing slash.
    //
    // name
    // This is the root name for the localization files. One- or two-part language
    // codes as per RFC 4646 (http://www.ietf.org/rfc/rfc4646.txt) are expected,
    // such as "en" for generic English, or "en-US" for American English versus
    // "en-AU" for Australian English. These are appended to the root name after
    // a dash. If your root filename was "profile", the Australian English version
    // would be "profile-en-AU.txt". For now, the plugin assumes the language files
    // use a .txt filename extension. A more convenient list of valid culture codes
    // (theoretically for .NET) is: http://sharpertutorials.com/list-of-culture-codes/
    $.linguaInit = function (path, name) {
        if (typeof path != 'string' || typeof name != 'string') {
            $.error('Invalid or missing parameter calling jQuery.lingua init method.');
        }
        else {
            lingua__path = path;
            lingua__name = name;
            lingua__lang = normalizeLang(navigator.language || navigator.userLanguage);
            // ------------------------- ^^ Mozilla            ^^ IE
        }
    }

    // string = $.linguaGetLanguage()
    // Returns the most recently language that the plugin attempted to load.
    $.linguaGetLanguage = function () {
        return lingua__lang;
    }

    // $.linguaLoad(language)
    // Retrieves the specified language file. This will autmatically degrade a specific
    // variant (such as fr-CA) to a generic variant (such as fr) and then to the default
    // non-language-specific base file if the language variants are not found. The demo
    // illustrates this behavior with the en-CA and ru languages. No return value.
    // Comments and blank lines are stripped, and #-prefixed names stored in a separate
    // array for automated processing as HTML element IDs.
    $.linguaLoad = function (language) { return doLoad(language) }
    function doLoad(language) {
        if (typeof lingua__path != 'string' || typeof lingua__name != 'string') {
            $.error('jQuery.lingua load method called prior to initialization.');
        }
        else {
            var lang = normalizeLang(language);
            var opts = { async: false, cache: false, dataType: 'text', timeout: 500 };
            var query;
            var file = "";

            // Load the language file and degrade along the way...
            if (lang.length >= 5) {
                query = $.ajax($.extend(opts, { url: lingua__path + lingua__name + '-' + lang.substring(0, 5) + '.txt' }));
                if (query.status == 200) file = query.responseText;
            }
            if (file.length == 0 && lang.length >= 2) {
                query = $.ajax($.extend(opts, { url: lingua__path + lingua__name + '-' + lang.substring(0, 2) + '.txt' }));
                if (query.status == 200) file = query.responseText;
            }
            if (file.length == 0) {
                query = $.ajax($.extend(opts, { url: lingua__path + lingua__name + '.txt' }));
                if (query.status == 200) file = query.responseText;
            }

            // Turn the language file content into an associative array
            // This general idea borrowed from Acatl Pacheco's ResourceBundle plugin.
            var rawText = $.trim(file).split("\n");
            lingua__data = {};
            lingua__eids = {};
            for (var i = 0; i < rawText.length; i++) {
                var row = $.trim(rawText[i]);
                var sep = row.indexOf("\t");
                if (sep == -1) sep = row.indexOf(" ");
                if (sep >= 0) {
                    var datkey = $.trim(row.slice(0, sep));                 // grab the key (trim to ignore blank lines)
                    if (datkey.substring(0, 2) != "//"                      // ignore comment lines
                       && datkey.substring(0, 3) != "Ôªø") {                // ignore UTF-8 signature line
                        if (datkey.charAt(0) == "#")
                            lingua__eids[datkey] = $.trim(row.slice(sep + 1)); // element IDs
                        else if(datkey.charAt(0) == "."){
                       		lingua__eids[datkey] = $.trim(row.slice(sep + 1)); // element IDs
                            lingua__data[datkey] = $.trim(row.slice(sep + 1)); // regular key/value pairs
                        }else
                            lingua__data[datkey] = $.trim(row.slice(sep + 1)); // regular key/value pairs
                    }
                }
            }
        }
    }

    // $.linguaUpdateElements()
    // Assuming the language file contained #-prefixed names, the assoc array will
    // be populated with the #-prefixed values that were parsed from the language
    // file. The key values are assumed to be HTML element IDs, and this method
    // loops through them and auto-updates their html() with the array values.
    $.linguaUpdateElements = function () { return doUpdateElements() }
    function doUpdateElements() {
        $.each(lingua__eids, function (key, value) {
            $(key).html(value);
        });
    }

    // $.linguaLoadAutoUpdate(language)
    // This just wraps the load function and the updateElements function so that
    // the caller can run both with a single command in the front-end code.
    $.linguaLoadAutoUpdate = function (language) {
        var dat = doLoad(language);
        doUpdateElements();
    }

    // This function borrowed from Keith Wood's Localise plugin.
    // Ensure language code is in the format aa-AA.
    function normalizeLang(lang) {
        lang = lang.replace(/_/, '-').toLowerCase();
        if (lang.length > 3) lang = lang.substring(0, 3) + lang.substring(3).toUpperCase();
        return lang;
    }

})(jQuery);