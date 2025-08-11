# --- run with bash (works fine on Windows if Git Bash is installed)
SHELL := bash

# Defaults (قابل تغییر موقع اجرا)
ORIGIN    ?= origin
MAIN      ?= main
BRANCHES  ?= A1 A2 A3 A4

# 1) سناریو: تغییرات main را روی A1..A4 بریز
.PHONY: sync-main
sync-main:
	@git fetch $(ORIGIN) --prune
	@for b in $(BRANCHES); do \
	  echo ">> Merge $(ORIGIN)/$(MAIN) into $$b"; \
	  git checkout $$b || exit 1; \
	  git pull --ff-only $(ORIGIN) $$b || true; \
	  git merge --no-ff --no-edit $(ORIGIN)/$(MAIN) || { echo "!! merge conflict on $$b"; exit 1; }; \
	  git push $(ORIGIN) $$b; \
	done
	@echo "✓ synced: $(BRANCHES) from $(MAIN)"

# 2) سناریو: یکی از A1..A4 آپدیت شده؛ همونو روی بقیه بریز
#   استفاده: make -f git.Makefile spread FROM=A2
.PHONY: spread
spread:
	@if [ -z "$(FROM)" ]; then echo "Usage: make -f git.Makefile spread FROM=<A1|A2|A3|A4>"; exit 2; fi
	@git fetch $(ORIGIN) --prune
	@echo ">> Source branch: $(FROM)"
	@git checkout $(FROM) && git pull --ff-only $(ORIGIN) $(FROM)
	@for b in $(BRANCHES); do \
	  [ "$$b" = "$(FROM)" ] && continue; \
	  echo ">> Merge $(ORIGIN)/$(FROM) into $$b"; \
	  git checkout $$b || exit 1; \
	  git pull --ff-only $(ORIGIN) $$b || true; \
	  git merge --no-ff --no-edit $(ORIGIN)/$(FROM) || { echo "!! merge conflict on $$b"; exit 1; }; \
	  git push $(ORIGIN) $$b; \
	done
	@echo "✓ spread $(FROM) into: $$(echo $(BRANCHES) | sed -e 's/$(FROM)//')"
