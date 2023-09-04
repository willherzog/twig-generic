# twig-generic
 These are some general-purpose extensions to the Twig templating engine.

 Note that the extension class must be initialized with the path within which the `file_exists` function will check for file existence.
 And if you prefer using spaces instead of tabs for the `indent_lines` filter, for the second constructor argument you'll need to set the amount of spaces to use for each level of indentation (positive integer = that number of spaces is used instead of a tab character).
## Filters
 * `indent_lines`: Apply the given levels of indent to each line of the input string; output is not automatically escaped.
 * `trim_trailing_newlines`: A more specific version of Twig's native `trim` filter, this only affects newline characters by default, only applies to the right side of the string, and its output is not automatically escaped since it is primarily intended to be used with HTML strings.
 * `enum_value`: Outputs the value (i.e. a string or integer) of a backed enumerator; outputs empty string if the input variable is NULL.
 * `limit_words`: Truncates the input string if it has more than the given number of words (as delimited by spaces) and, if so, appends an ellipsis.
 * `underscore_to_dash`: Converts underscore characters to dash characters except that, by default, multiple adjacent underscores are preserved.
 * `title_conditional`: A more conservative version of Twig's native `title` filter, this only applies case folding to the input string when all of its letters are lower case.
 * `initials`: Outputs only the first letters of the words of the input string (as delimited by spaces), each with an optional suffix appended and with optional separator character(s) between them; only uses the first and last words by default.
 * `percentage`: Converts a decimal value to a percentage string (e.g. `0.05` becomes "5%").
## Functions
 * `file_exists`: Check whether the given file path resolves to an existing, readable file.
 * `timezone`: Returns the current default PHP timezone (as set with `date_default_timezone_set()` or the PHP config option `date.timezone`).
