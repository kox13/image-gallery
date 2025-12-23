# Image Gallery Application

A PHP-based image gallery application with MongoDB backend, built with Docker for development.

## Development Notice

This is **development code**. For the application to work correctly, you **must** run `make permissions` after starting the containers to set proper file ownership for image uploads.

## Features

- User registration and authentication
- Image upload with watermarking
- Thumbnail generation
- Private/public image galleries
- Favorites system
- MongoDB database backend

## Requirements

- Docker & Docker Compose
- Make
- Linux/WSL (for proper file permissions)

## Quick Start

1. **Clone the repository**
   ```bash
   git clone https://github.com/kox13/image-gallery.git
   ```

2. **Start the application**
   ```bash
   make start
   ```

3. **Set up permissions (Required)**
   ```bash
   make permissions
   ```
   
   > **Important**: You may need to log out and back in after running this command for group permissions to take effect.

4. **Access the application**
   - Application: http://localhost:8080
   - MongoDB: localhost:27017

## Makefile Targets

### `make start`
Starts the Docker containers in detached mode.

```bash
make start
```

### `make stop`
Stops all containers and clears cached images.

```bash
make stop
```

### `make perm`
Sets correct file ownership and permissions for the application to handle image uploads. This command:
- Changes ownership of project files to `www-data:www-data`
- Sets directory permissions to 775
- Adds your current user to the `www-data` group

```bash
make perm
```

**Note**: After running this command for the first time, log out and back in for group membership changes to take effect.

### `make clcache`
Removes all uploaded images from the cache directory.

```bash
make clcache
```

### `make cpvendor`
Copies the vendor folder from the Docker container to the host machine. Useful for IDE autocompletion and local development.

```bash
make cpvendor
```

### `make rmv`
Removes all Docker volumes associated with the project (vendor_data, mongodb_data, mongodb_config).

```bash
make rmv
```

## Project Structure

```
image-gallery/
├── src/
│   ├── classes/           # Application classes
│   │   ├── controllers/   # MVC controllers
│   │   ├── models/        # Database models
│   │   ├── view/          # View handling
│   │   ├── App.php        # Main application class
│   │   └── Database.php   # Database connection
│   ├── views/             # View templates
│   ├── web/               # Public web root
│   │   ├── static/        # CSS, fonts, static images
│   │   ├── images/        # User uploaded images (git-ignored)
│   │   └── front_controller.php
│   ├── admin.js           # MongoDB admin user init script
│   └── user.js            # MongoDB app user init script
├── docker-compose.yml     # Docker services configuration
├── Dockerfile             # PHP-Apache container definition
├── Makefile              # Build and utility commands
└── README.md             # This file
```

## Configuration

### Database
- **Database name**: `gallery`
- **Connection**: Configured in `src/classes/Database.php`
- **Host**: `mongodb:27017` (internal Docker network)

### PHP Settings
- **Memory limit**: 512M
- **Upload max filesize**: 1MB

## Development Workflow

1. Start containers: `make start`
2. Set permissions: `make permissions` (first time only)
3. Develop and test
4. Stop containers: `make stop` (when done)
