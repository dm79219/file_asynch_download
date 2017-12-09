# Sql Query Aggregator
Php library to run query in chunks asynchronously and downloading the result in CSV

# install

composer require dm79219/file_asynch_download @dev


# use
1. In src/connection/dbconfig.php file, add your slave db credentials.
2. For use refer example/test.php file

# output
Array
(
    [status] => 1
    [message] => success
    [url] => relative_path_of_csv_file
)

# NOTE
for using this, your user should have permission to run shell_exec command from php code
