CURRENT_USER := $(shell whoami)

perm:
	@echo "Setting www-data ownership for project files..."
	sudo chown -R www-data:www-data .
	sudo chmod -R 775 .
	@echo "Done! Files in container are now owned by www-data."
	@echo "Adding $(CURRENT_USER) to www-data group..."
	@if ! groups $(CURRENT_USER) | grep -q www-data; then \
		sudo usermod -a -G www-data $(CURRENT_USER); \
		echo "User added to www-data group."; \
		echo "You may need to log out and back in for changes to take effect."; \
	else \
		echo "$(CURRENT_USER) is already in www-data group."; \
	fi

clcache:
	sudo rm -rf ./src/web/images/*.jpg
	sudo rm -rf ./src/web/images/*.png

start:
	docker compose up -d

stop:
	docker compose down
	make clcache

rmv:
	docker volume rm image-gallery_vendor_data
	docker volume rm image-gallery_mongodb_data
	docker volume rm image-gallery_mongodb_config

cpvendor:
	sudo docker compose cp app:/var/www/html/vendor ./vendor