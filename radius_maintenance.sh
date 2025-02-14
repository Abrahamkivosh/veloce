#!/bin/bash

# MySQL Credentials
DB_HOST="localhost"
DB_PORT="3306"
DB_NAME="new_radius"
DB_USER="root"
DB_PASSWORD="@christanetworks7879"

# Backup directory (change if needed)
BACKUP_DIR="/var/backups/mysql"
TIMESTAMP=$(date +"%Y-%m-%d_%H-%M-%S")
BACKUP_FILE="$BACKUP_DIR/new_radius_backup_$TIMESTAMP.sql"

# Ensure the backup directory exists
mkdir -p "$BACKUP_DIR"

# Delete records older than 2 days from radpostauth
mysql -h "$DB_HOST" -P "$DB_PORT" -u "$DB_USER" -p"$DB_PASSWORD" -D "$DB_NAME" -e "
DELETE FROM radpostauth WHERE authdate < NOW() - INTERVAL 2 DAY;
"

# Backup the database (every 6 months)
if [ "$(date +%m)" -eq 01 ] || [ "$(date +%m)" -eq 07 ]; then
    echo "Starting database backup..."
    mysqldump -h "$DB_HOST" -P "$DB_PORT" -u "$DB_USER" -p"$DB_PASSWORD" "$DB_NAME" > "$BACKUP_FILE"
    
    if [ $? -eq 0 ]; then
        echo "Backup successful: $BACKUP_FILE"
    else
        echo "Backup failed!"
    fi
fi
