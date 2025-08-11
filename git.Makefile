# =========================
# git.Makefile - Git branch sync helpers
# =========================
# Vars (override at call time):
#   ORIGIN=origin
#   MAIN=main
#   BRANCHES="A1 A2 A3 A4"
#   SYNC_STRATEGY=merge|rebase
#   PUSH=1|0
#   DRY_RUN=1|0
# =========================

SHELL := /bin/sh

ORIGIN        ?= origin
MAIN          ?= main
BRANCHES      ?= A1 A2 A3 A4
SYNC_STRATEGY ?= merge             # merge | rebase
PUSH          ?= 1                 # 1=push after sync
DRY_RUN       ?= 0                 # 1=print commands only

define ensure_clean
	@test -z "$$(git status --porcelain)" || \
	( echo "✖ Working tree clean نیست. Commit/Stash کن بعداً دوباره اجرا کن."; exit 2 )
endef

.PHONY: git-help
git-help:
	@echo ""
	@echo "Git sync targets:"
	@echo "  make sync-from-main BRANCHES=\"A1 A2 A3 A4\" [SYNC_STRATEGY=merge|rebase] [PUSH=1|0] [DRY_RUN=1]"
	@echo "  make sync-all-branches [SYNC_STRATEGY=merge|rebase] [PUSH=1|0] [DRY_RUN=1]"
	@echo ""
	@echo "Vars: ORIGIN=$(ORIGIN)  MAIN=$(MAIN)  SYNC_STRATEGY=$(SYNC_STRATEGY)  PUSH=$(PUSH)  DRY_RUN=$(DRY_RUN)"
	@echo ""

# -------------------------
# Sync specific branches from MAIN
# -------------------------
.PHONY: sync-from-main
sync-from-main:
	@$(ensure_clean)
	@git fetch $(ORIGIN) --prune
	@orig_branch=$$(git rev-parse --abbrev-ref HEAD || echo HEAD); \
	for b in $(BRANCHES); do \
	  echo "———> Sync $$b from $(ORIGIN)/$(MAIN) [strategy=$(SYNC_STRATEGY)]"; \
	  # ensure local branch exists (track remote if only remote exists)
	  if git show-ref --verify --quiet refs/heads/$$b; then \
	    if [ "$(DRY_RUN)" = "1" ]; then echo "  CMD: git checkout $$b"; else git checkout $$b; fi; \
	  elif git ls-remote --exit-code --heads $(ORIGIN) $$b >/dev/null 2>&1; then \
	    if [ "$(DRY_RUN)" = "1" ]; then echo "  CMD: git checkout -b $$b --track $(ORIGIN)/$$b"; \
	    else git checkout -b $$b --track $(ORIGIN)/$$b; fi; \
	  else \
	    echo "    • Skip $$b (نه لوکال هست نه روی ریموت)"; \
	    continue; \
	  fi; \
	  if [ "$(SYNC_STRATEGY)" = "rebase" ]; then \
	    if [ "$(DRY_RUN)" = "1" ]; then echo "  CMD: git rebase $(ORIGIN)/$(MAIN)"; \
	    else git rebase $(ORIGIN)/$(MAIN) || { echo "!! Rebase conflict on $$b. حل کن و 'git rebase --continue' بزن."; exit 1; }; fi; \
	    if [ "$(PUSH)" = "1" ]; then \
	      if [ "$(DRY_RUN)" = "1" ]; then echo "  CMD: git push --force-with-lease $(ORIGIN) $$b"; \
	      else git push --force-with-lease $(ORIGIN) $$b; fi; \
	    fi; \
	  else \
	    if [ "$(DRY_RUN)" = "1" ]; then echo "  CMD: git merge --no-ff --no-edit $(ORIGIN)/$(MAIN)"; \
	    else git merge --no-ff --no-edit $(ORIGIN)/$(MAIN) || { echo "!! Merge conflict on $$b."; exit 1; }; fi; \
	    if [ "$(PUSH)" = "1" ]; then \
	      if [ "$(DRY_RUN)" = "1" ]; then echo "  CMD: git push $(ORIGIN) $$b"; \
	      else git push $(ORIGIN) $$b; fi; \
	    fi; \
	  fi; \
	done; \
	if [ "$(DRY_RUN)" = "1" ]; then echo "CMD: git checkout $$orig_branch"; else git checkout $$orig_branch; fi; \
	echo "✓ Done."

# -------------------------
# (Risky) Sync every local branch with main and all others
# -------------------------
.PHONY: sync-all-branches
sync-all-branches:
	@$(ensure_clean)
	@git fetch $(ORIGIN) --prune
	@orig_branch=$$(git rev-parse --abbrev-ref HEAD || echo HEAD); \
	branches="$$(git for-each-ref --format='%(refname:short)' refs/heads)"; \
	for target in $$branches; do \
	  echo "=== Target: $$target [strategy=$(SYNC_STRATEGY)]"; \
	  if [ "$(DRY_RUN)" = "1" ]; then echo "CMD: git checkout $$target"; else git checkout $$target; fi; \
	  # first from main
	  if [ "$(SYNC_STRATEGY)" = "rebase" ]; then \
	    if [ "$(DRY_RUN)" = "1" ]; then echo "  CMD: git rebase $(ORIGIN)/$(MAIN)"; \
	    else git rebase $(ORIGIN)/$(MAIN) || { echo "!! Rebase conflict on $$target from $(MAIN)"; exit 1; }; fi; \
	  else \
	    if [ "$(DRY_RUN)" = "1" ]; then echo "  CMD: git merge --no-ff --no-edit $(ORIGIN)/$(MAIN)"; \
	    else git merge --no-ff --no-edit $(ORIGIN)/$(MAIN) || { echo "!! Merge conflict on $$target from $(MAIN)"; exit 1; }; fi; \
	  fi; \
	  # then from every other branch
	  for source in $$branches; do \
	    [ "$$source" = "$$target" ] && continue; \
	    echo " -> from $$source"; \
	    if [ "$(SYNC_STRATEGY)" = "rebase" ]; then \
	      if [ "$(DRY_RUN)" = "1" ]; then echo "    CMD: git rebase $(ORIGIN)/$$source"; \
	      else git rebase $(ORIGIN)/$$source || { echo "!! Rebase conflict on $$target from $$source"; exit 1; }; fi; \
	    else \
	      if [ "$(DRY_RUN)" = "1" ]; then echo "    CMD: git merge --no-ff --no-edit $(ORIGIN)/$$source"; \
	      else git merge --no-ff --no-edit $(ORIGIN)/$$source || { echo "!! Merge conflict on $$target from $$source"; exit 1; }; fi; \
	    fi; \
	  done; \
	  if [ "$(PUSH)" = "1" ]; then \
	    if [ "$(DRY_RUN)" = "1" ]; then echo "CMD: git push $(if [ "$(SYNC_STRATEGY)" = "rebase" ]; then echo '--force-with-lease'; fi) $(ORIGIN) $$target"; \
	    else if [ "$(SYNC_STRATEGY)" = "rebase" ]; then git push --force-with-lease $(ORIGIN) $$target; else git push $(ORIGIN) $$target; fi; fi; \
	  fi; \
	done; \
	if [ "$(DRY_RUN)" = "1" ]; then echo "CMD: git checkout $$orig_branch"; else git checkout $$orig_branch; fi; \
	echo "✓ Done."
