# twig-generic
 These are some general-purpose extensions to the Twig templating engine.
 Includes the custom filters `underscore_to_dash`, `indent_lines`, `percentage`, `title_conditional`, `enum_value`, `limit_words` and `initials` and the custom functions `timezone` and `file_exists`.
 Note that this Twig extension must be initialized with the path within which the `file_exists` function will check for file existence.
 And if you prefer using spaces instead of tabs for the `indent_lines` filter, for the second constructor argument you'll need to set the amount of spaces to use for each level of indentation (positive integer = that number of spaces is used instead of a tab character).
