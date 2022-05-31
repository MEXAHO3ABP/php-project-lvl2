#Makefile
install: #Установка composer
	composer install
gendiff: #Запуск gendiff
	./bin/gendiff
lint: #Проверка линтером php-codesniffer по стандарту PSR12
	composer exec --verbose phpcs -- --standard=PSR12 src bin