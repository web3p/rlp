# rlp

PHP implementation of Ethereum Recursive Length Prefix (RLP) encoding and decoding.

## Repository architecture

- `src/RLP.php`: public encode/decode API and recursive RLP parsing/encoding helpers.
- `src/Types/Str.php`: string and hexadecimal conversion helpers.
- `src/Types/Numeric.php`: numeric-to-hex conversion.
- `test/unit/`: PHPUnit tests.
- `phpunit.xml`: test configuration.

This library is consumed by Ethereum-related code, so byte-level compatibility matters more than stylistic cleanup.

## Development

Install dependencies:

    composer install

Run all tests:

    vendor/bin/phpunit

Run a focused test:

    vendor/bin/phpunit --filter <test-name>

Validate Composer metadata:

    composer validate

Syntax-check a changed PHP file:

    php -l <file>

Before assuming another command exists, inspect the repository configuration.

## Working principles

Before modifying code:

1. Read the relevant implementation and tests.
2. Establish intended RLP behavior before editing protocol-sensitive code.
3. Prefer the smallest change that solves the selected issue.
4. Preserve the public API and supported PHP versions unless explicitly changing them.
5. Do not introduce dependencies without a clear need.
6. Do not combine protocol fixes, dependency upgrades, and broad refactors.
7. Do not modify unrelated files.

Do not speculate about code you have not inspected.

## RLP correctness

Treat encoding and decoding as protocol-sensitive. Check especially:

- the single-byte rule for values in `[0x00, 0x7f]`
- empty byte strings and empty lists
- short strings/lists (payload length 0-55)
- long strings/lists (payload length >= 56)
- length-of-length encoding
- canonical/minimal encodings
- truncated or malformed payloads
- nested lists
- trailing/remainder data
- hex validation and `0x` handling
- odd-length hex input and leading zeroes
- zero, null, numeric strings, integers, and large numeric values
- byte length versus character length
- PHP integer/float precision

Do not simplify encoding code unless byte-for-byte equivalence is demonstrated by tests.

For correctness fixes, add deterministic regression vectors.

## PHP compatibility

`composer.json` currently supports PHP `^7.1 | ^8.0`.

Do not introduce syntax unavailable on PHP 7.1 unless the supported PHP range is intentionally changed in a separate task. Consider public API compatibility before adding native parameter or return types.

## Review workflow

When asked to review:

- inspect implementation and tests first
- report findings before changing code
- separate confirmed correctness issues from modernization/style opportunities
- explain evidence for each finding
- do not modify files unless explicitly asked

When asked to implement:

1. identify the selected finding
2. inspect `git status`
3. make only the required change
4. add/update tests
5. run focused tests
6. run the full suite when practical
7. inspect the final diff

## Git workflow

When multiple findings exist, handle them one at a time unless explicitly requested otherwise.

For each selected issue:

1. Check `git status`; preserve unrelated user changes.
2. Start from the appropriate base branch.
3. Create a focused branch: `fix/`, `feat/`, `refactor/`, `test/`, or `chore/`.
4. Implement only that issue.
5. Add/update tests.
6. Run relevant tests.
7. Review `git diff`.
8. Stage only task-related files.
9. If explicitly asked, create one focused commit.
10. Do not push unless explicitly requested.
11. Stop before starting the next finding.

Never use destructive Git commands without explicit approval. Never amend an existing commit unless explicitly requested. If another finding is required, explain the dependency first.
