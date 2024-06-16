#!/bin/bash
# This script is used to upgrade the system for the Permissions required for file handling.



# Define the sudoers file, the source file, and the script file
SUDOERS_FILE="/etc/sudoers.d/svxlink"
SOURCE_FILE="www-data.sudoers"
SCRIPT_FILE=$(basename "$0")

# Function to display an info message using whiptail
show_info() {
  whiptail --title "Information" --msgbox "$1" 8 78
}

# Check if the source file exists
if [ ! -f "$SOURCE_FILE" ]; then
  whiptail --title "Error" --msgbox "Source file $SOURCE_FILE does not exist. Exiting." 8 78
  exit 1
fi

# Check if the sudoers file exists
if [ -f "$SUDOERS_FILE" ]; then
  : > "$SUDOERS_FILE"
else
  touch "$SUDOERS_FILE"
fi

# Ensure the sudoers file has the correct permissions
chmod 0440 "$SUDOERS_FILE"

# Read the content from the source file into the sudoers file
cat "$SOURCE_FILE" > "$SUDOERS_FILE"

# Inform the user that the operation was successful
show_info "Content from $SOURCE_FILE has been written to $SUDOERS_FILE successfully."

# Validate the syntax of the sudoers file
visudo -cf "$SUDOERS_FILE"
if [ $? -eq 0 ]; then
  show_info "The $SUDOERS_FILE syntax is valid."
else
  whiptail --title "Error" --msgbox "The $SUDOERS_FILE contains syntax errors. Please check the file." 8 78
  exit 1
fi

# Change ownership of all files in /var/www/html except the script itself
find /var/www/html ! -name "$SCRIPT_FILE" -exec sudo chown svxlink:svxlink {} +

# Inform the user that the ownership change was successful
show_info "Ownership of files in /var/www/html has been changed to svxlink:svxlink, except for the script itself."
