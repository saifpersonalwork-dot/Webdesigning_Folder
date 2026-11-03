# Git and GitHub workflow (Lab Sheet 10, Q1–2)

Run these commands from the project folder:

```bash
git init
git add .
git commit -m "Initial web lab project"
git branch feature/product-search
# Make and save your feature changes, then:
git add .
git commit -m "Add product search feature"
git switch main
git merge feature/product-search
git remote add origin https://github.com/YOUR_USERNAME/YOUR_REPOSITORY.git
git push -u origin main
```

## Practice a merge conflict
1. On `main`, edit the same line in `README.md`, commit it.
2. Run `git switch feature/product-search` and edit that same line differently; commit.
3. Run `git switch main` then `git merge feature/product-search`.
4. Git marks the conflicting lines with `<<<<<<<`, `=======`, and `>>>>>>>`.
5. Edit the file to keep the correct final text, remove all markers, then run:

```bash
git add README.md
git commit -m "Resolve README merge conflict"
git push
```

Replace the GitHub URL and repository name with your own.
