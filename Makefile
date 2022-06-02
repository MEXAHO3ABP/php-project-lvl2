#Makefile
install: #Установка composer
	composer install
gendiff: #Запуск gendiff
	./bin/gendiff
lint: #Проверка линтером php-codesniffer по стандарту PSR12
	composer exec --verbose phpcs -- --standard=PSR12 src bin
	composer exec --verbose phpstan -- --level=8 analyse src tests
test: #Запуск функциональных тестов
	composer exec --verbose phpunit tests
test-coverage: #Запуск проверки на покрытие кода
	composer exec --verbose phpunit tests -- --coverage-clover build/logs/clover.xml
