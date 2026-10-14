# rlp

PHP impl of Ethereum Recursive Length Prefix (RLP) encode/decode.

## Repository architecture

- `src/RLP.php`: public encode/decode API + recursive RLP parse/encode helpers.
- `src/Types/Str.php`: string/hex conversion helpers.
- `src/Types/Numeric.php`: numeric-to-hex conversion.
- `test/unit/`: PHPUnit tests.
- `phpunit.xml`: test config.

Consumed by Ethereum code. Byte-level compat > stylistic cleanup.

## Development

Install deps:

    composer install

Run all tests:

    vendor/bin/phpunit

Run focused test:

    vendor/bin/phpunit --filter <test-name>

Validate Composer metadata:

    composer validate

Syntax-check changed PHP file:

    php -l <file>

Inspect repo config before assuming other command exists.

## Working principles

Before modifying code:

1. Read relevant impl + tests.
2. Establish intended RLP behavior before editing protocol-sensitive code.
3. Smallest change solving selected issue.
4. Preserve public API + supported PHP versions unless explicitly changing.
5. No new deps without clear need.
6. No mixing protocol fixes, dep upgrades, broad refactors.
7. No touching unrelated files.

No speculating about uninspected code.

## RLP correctness

Encode/decode = protocol-sensitive. Check especially:

- single-byte rule for values in `[0x00, 0x7f]`
- empty byte strings, empty lists
- short strings/lists (payload len 0-55)
- long strings/lists (payload len >= 56)
- length-of-length encoding
- canonical/minimal encodings
- truncated/malformed payloads
- nested lists
- trailing/remainder data
- hex validation, `0x` handling
- odd-length hex input, leading zeroes
- zero, null, numeric strings, ints, large numerics
- byte length vs char length
- PHP int/float precision

No simplifying encoding code unless tests prove byte-for-byte equivalence.

Correctness fixes: add deterministic regression vectors.

## PHP compatibility

`composer.json` currently supports PHP `^7.1 | ^8.0`.

No syntax unavailable on PHP 7.1 unless supported range intentionally changed in separate task. Consider public API compat before adding native param/return types.

## Review workflow

Review request:

- inspect impl + tests first
- report findings before changing code
- separate confirmed correctness issues from modernization/style
- give evidence per finding
- no file edits unless explicitly asked

Implement request:

1. identify selected finding
2. inspect `git status`
3. only required change
4. add/update tests
5. run focused tests
6. run full suite when practical
7. inspect final diff

## Git workflow

Multiple findings: one at a time unless told otherwise.

Per selected issue:

1. Check `git status`; preserve unrelated user changes.
2. Start from right base branch.
3. Create focused branch: `fix/`, `feat/`, `refactor/`, `test/`, or `chore/`.
4. Implement only that issue.
5. Add/update tests.
6. Run relevant tests.
7. Review `git diff`.
8. Stage only task files.
9. If explicitly asked, one focused commit.
10. No push unless explicitly requested.
11. Stop before next finding.

No destructive Git commands without explicit approval. No amending existing commit unless explicitly requested. If another finding required, explain dependency first.