# Tasks 1–2: Git repository, branches, merge conflict, GitHub

```bash
git init
git branch -M main
cat > .gitignore <<'EOF'
/vendor/
/config/config.php
/uploads/*
!/uploads/.gitkeep
.env
*.log
.DS_Store
EOF
git add .
git commit -m "Initial MVC application structure"

git switch -c feature/product-description
# Edit a file, e.g. README.md, then:
git add README.md
git commit -m "Add product description feature"

git switch main
# Make a different change to the same line in README.md and commit:
git add README.md
git commit -m "Update project overview"

git merge feature/product-description
# If Git reports a conflict, open the conflicted file, choose the correct
# content, remove conflict markers, then:
git add README.md
git commit -m "Resolve README merge conflict"

git remote add origin https://github.com/YOUR-USERNAME/YOUR-REPOSITORY.git
git push -u origin main
git push -u origin feature/product-description
```

To deliberately create a conflict, edit the same line in the same file differently on `main` and on the feature branch before merging. Replace the GitHub URL with your own repository URL.
