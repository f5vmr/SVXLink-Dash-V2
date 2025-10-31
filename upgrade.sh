#!/bin/bash
# This script is used to upgrade the system for the Permissions required for file handling.

# Define the sudoers file, the source file, the script file, and the config file
SUDOERS_FILE="/etc/sudoers.d/svxlink"
SOURCE_FILE="www-data.sudoers"
SCRIPT_FILE=$(basename "$0")
CONFIG_FILE="include/config.inc.php"

# Function to display an info message using whiptail
show_info() {
  whiptail --title "Information" --msgbox "$1" 8 78
}

# Prompt the user for their dashboard username
DASHBOARD_USER=$(whiptail --title "Dashboard Username" --inputbox "Please enter your dashboard username:" 8 78 svxlink 3>&1 1>&2 2>&3)

# Prompt the user for their dashboard password
DASHBOARD_PASSWORD=$(whiptail --title "Dashboard Password" --passwordbox "Please enter your dashboard password:" 8 78 3>&1 1>&2 2>&3)

# Update the config file with the provided username and password
if [ -f "$CONFIG_FILE" ]; then
  sed -i "s/define(\"PHP_AUTH_USER\", \".*\");/define(\"PHP_AUTH_USER\", \"$DASHBOARD_USER\");/" "$CONFIG_FILE"
  sed -i "s/define(\"PHP_AUTH_PW\", \".*\");/define(\"PHP_AUTH_PW\", \"$DASHBOARD_PASSWORD\");/" "$CONFIG_FILE"
  show_info "The config file $CONFIG_FILE has been updated with the new username and password."
else
  whiptail --title "Error" --msgbox "Config file $CONFIG_FILE does not exist. Exiting." 8 78
  exit 1
fi

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
# Change ownership of all files in /etc/svxlink and sub directories.
find /etc/svxlink -type f -exec sudo chown svxlink:svxlink {} +
# Set DTMF active.
sudo mkdir -p /var/run/svxlink
sudo chown svxlink:svxlink /var/run/svxlink
sudo chmod 775 /var/run/svxlink

#find the terminal active.
# Inform the user that the ownership change was successful
show_info "Ownership of files in /var/www/html has been changed to svxlink:svxlink, except for the script itself."
# ==============================
# Node.js, npm, and svxlink-node.service setup
# ==============================

# Check if Node.js is installed
if ! command -v node >/dev/null 2>&1; then
    show_info "Node.js not found. Installing Node.js and npm..."
    sudo apt update
    sudo apt install -y nodejs npm
else
    show_info "Node.js is already installed: $(node -v)"
fi

# Check if npm is installed (optional)
if ! command -v npm >/dev/null 2>&1; then
    show_info "npm not found. Installing npm..."
    sudo apt install -y npm
else
    show_info "npm is already installed: $(npm -v)"
fi

# Ensure ws module is installed for svxlink user in scripts folder
SCRIPT_DIR="/var/www/html/scripts"
if [ ! -d "$SCRIPT_DIR/node_modules/ws" ]; then
    show_info "Installing ws Node module for svxlink user..."
    sudo -u svxlink bash -c "cd $SCRIPT_DIR && npm install ws"
else
    show_info "Node module ws is already installed."
fi

# Create the systemd service only if it doesn't exist
SERVICE_FILE="/etc/systemd/system/svxlink-node.service"
if [ ! -f "$SERVICE_FILE" ]; then
    show_info "Creating svxlink-node.service..."
    sudo tee "$SERVICE_FILE" > /dev/null <<EOL
[Unit]
Description=SVXLink Node.js Server
After=network-online.target
Wants=network-online.target

[Service]
Type=simple
User=svxlink
Group=svxlink
ExecStart=/usr/bin/node $SCRIPT_DIR/server.js
Restart=on-failure
RestartSec=5
Environment=NODE_ENV=production

[Install]
WantedBy=multi-user.target
EOL

    show_info "Reloading systemd, enabling, and starting svxlink-node.service..."
    sudo systemctl daemon-reload
    sudo systemctl enable svxlink-node.service
    sudo systemctl start svxlink-node.service
else
    show_info "svxlink-node.service already exists."
    # Optional: restart it to ensure it's running
    sudo systemctl restart svxlink-node.service
fi

# Verify service status
sudo systemctl is-active --quiet svxlink-node.service && show_info "svxlink-node.service is running." || show_info "svxlink-node.service is not running!"

# ==============================
# Create /home/pi/scripts/dtmf_setup.sh if missing and run it
# ==============================

DTMF_SCRIPT="/home/pi/scripts/dtmf_setup.sh"

if [ ! -f "$DTMF_SCRIPT" ]; then
    show_info "Creating $DTMF_SCRIPT..."
    sudo mkdir -p /home/pi/scripts
    echo "#!/bin/sh
sudo mkdir -p /var/run/svxlink
sudo chown svxlink:svxlink /var/run/svxlink
sudo chmod 775 /var/run/svxlink" | sudo tee "$DTMF_SCRIPT" > /dev/null

    sudo chmod +x "$DTMF_SCRIPT"
    show_info "$DTMF_SCRIPT created and made executable."
else
    show_info "$DTMF_SCRIPT already exists."
fi

# Run the script immediately
show_info "Running $DTMF_SCRIPT..."
sudo "$DTMF_SCRIPT"

# New section to create /home/pi/scripts and cleanup.sh
SCRIPT_DIR="/home/pi/scripts"
CLEANUP_SCRIPT="$SCRIPT_DIR/cleanup.sh"

# Check if the script directory exists, if not, create it
if [ ! -d "$SCRIPT_DIR" ]; then
    mkdir -p "$SCRIPT_DIR"
    show_info "Created directory $SCRIPT_DIR"
fi

# Check if the cleanup.sh script exists
if [ -f "$CLEANUP_SCRIPT" ]; then
    show_info "Script $CLEANUP_SCRIPT already exists. Exiting."
    exit 0
else
    # Create the cleanup.sh script with the specified content
    echo "#!/bin/bash

# Directory to be cleaned
DIR=\"/var/www/html/backups\"

# Check if directory exists
if [ -d \"\$DIR\" ]; then
    # Find and delete files older than 7 days
    find \"\$DIR\" -type f -mtime +7 -exec rm -f {} \;
else
    echo \"Directory \$DIR does not exist.\"
fi" > "$CLEANUP_SCRIPT"

    # Make the cleanup.sh script executable
    sudo chmod +x "$CLEANUP_SCRIPT"
    show_info "Created and made $CLEANUP_SCRIPT executable."
fi

# Check and add the cleanup.sh script to the sudo crontab if not already present
CRON_JOB="01 00 * * * /home/pi/scripts/cleanup.sh"
( sudo crontab -l | grep -q "$CRON_JOB" ) || ( sudo crontab -l; echo "$CRON_JOB" ) | sudo crontab -

# Inform the user that the crontab entry has been added if it was not present
show_info "Ensured that the crontab entry for $CLEANUP_SCRIPT exists."
