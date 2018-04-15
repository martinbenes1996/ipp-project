
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
	tar -zcvf xbenes49.tgz rozsireni doc.pdf tests/* *.php *.py > /dev/null 2> /dev/null
	@printf "";\
	rm doc.pdf

# doc
.PHONY: doc
doc:
	@echo "Generate documentation.";\
	$(MAKE) -C doc/  > /dev/null

# clean
.PHONY: clean
clean:
	@echo "Cleaning generated files.";\
	rm -rf doc/dokumentace.aux doc/dokumentace.pdf doc/dokumentace.log
