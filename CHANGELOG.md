## 0.3 (May 18, 2016)

Improvements:
- Restructure the database ORM to have a singleton factory to fetch the PDO connection and not recreate it every time
- Reformat the expense input to not set negative numbers
- Change the calculation of remaining balance to reflect to the new change of expenses
- Update Vagrantfile to port forward the Mailcatcher ports and set a timeout for devices that take time to boot the VM
- Updated README.md

## 0.2 (May 14, 2016)

Improvements:
- Restructure the seed module
- Added types for the seed script so you can create easily new seed files and to execute them
- Updated README.md
