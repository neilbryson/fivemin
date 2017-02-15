# FiveMin
Removes rows on CSV files that are not within the minute interval.

# System Requirements
 * PHP >= 7.0.15
 * [Composer](https://getcomposer.org)

# Installation
1. Clone the repository
2. Run `composer install`
3. All set. *Allons-y !*

# Usage
To run a command, run `php fivemin command [options] [arguments]`

# Commands
1. go
    * (`php fivemin go [options] [file_location] [date_time_header_name]`)
      * Options
        * **--interval** (-i)
          * The interval length in minutes. Default is 5.
        * **--save-file-name** (-name)
          * The name of the new file
        * **--save-dir** (-d)
          * Location on where to save the CSV file
