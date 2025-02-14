#!/bin/bash

# Database details (No need to include credentials)
DB_NAME="new_radius"

# Backup directory
BACKUP_DIR="/var/backups/mysql"
TIMESTAMP=$(date +"%Y-%m-%d_%H-%M-%S")
BACKUP_FILE="$BACKUP_DIR/new_radius_backup_$TIMESTAMP.sql"

# Ensure the backup directory exists
mkdir -p "$BACKUP_DIR"

# Delete records older than 2 days from radpostauth
mysql --login-path=newradius -D "$DB_NAME" -e "
DELETE FROM radpostauth WHERE authdate < NOW() - INTERVAL 2 DAY;
"

# Backup the database (every 6 months)
if [ "$(date +%m)" -eq 01 ] || [ "$(date +%m)" -eq 07 ]; then
    echo "Starting database backup..."
    mysqldump --login-path=newradius "$DB_NAME" > "$BACKUP_FILE"
    
    if [ $? -eq 0 ]; then
        echo "Backup successful: $BACKUP_FILE"
    else
        echo "Backup failed!"
    fi
fi
