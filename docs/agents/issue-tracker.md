# Issue tracker: GitHub

Issues and specs live in netzhuffle/BoardGameShelf on GitHub.
Use the gh CLI from this repository.

PRs as a request surface: yes.

Read issues with gh issue view and PRs with gh pr view,
gh pr view --comments, and gh pr diff. Use the corresponding
issue or PR commands to comment, label, and close.

Before creating an issue, verify the target with
gh repo view --json nameWithOwner.
For multiline bodies, write a temporary file and use --body-file.

Use native GitHub sub-issues and issue dependencies when available.
Otherwise record parent links as “Part of #<parent>” and blockers
as “Blocked by: #<number>”. Work is unblocked when all blockers close.

When a skill says “publish to the issue tracker”, create an issue.
When it says “fetch the relevant ticket”, read the issue or PR.
GitHub shares issue and PR numbers; resolve which kind it is first.
