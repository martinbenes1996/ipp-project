
# Makefile.
#
# Author: xbenes49
# Copyright: Martin Benes (c) 2018

# zip
.PHONY: zip
zip:
	@echo "Zipping files.";\
	cp doc/dokumentace.pdf doc.pdf
	@printf "";\
	tar -zcvf xbenes49.tgz rozsireni doc.pdf tests/* *.php *.py Makefile > /dev/null 2> /dev/null
	@printf "";\
	rm doc.pdf
