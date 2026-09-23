# RLP Code Review

Review this repository as a senior PHP library engineer with particular attention to Ethereum RLP correctness.

## Process

1. Read `CLAUDE.md`.
2. Inspect `composer.json`, `phpunit.xml`, relevant `src/` files, and tests.
3. Trace helpers/callers before declaring behavior incorrect.
4. Prefer correctness and interoperability findings over style.
5. Do not modify code during review unless explicitly requested.

## Protocol checklist

Check:

- canonical RLP encoding
- scalar/string/list prefix boundaries
- payload lengths 0, 1, 55, 56 and larger boundaries
- length-of-length encoding
- empty string/list
- nested lists
- canonical single-byte encoding
- non-canonical encodings
- malformed/truncated input
- trailing/remainder bytes
- hex validation and `0x`
- odd-length hex and leading zeroes
- numeric zero, negatives, numeric strings
- values beyond PHP integer precision
- UTF-8/raw-byte handling
- `mb_*` usage where byte semantics matter
- recursion on malformed nested data

Do not call something a protocol bug unless you can state the invariant and show the violation.

## PHP/library checklist

Also inspect:

- PHP 7.1 compatibility
- public API compatibility
- native types versus PHPDoc
- exception behavior
- dependency constraints
- PHPUnit configuration
- test gaps
- duplicated/confusing code
- dead code
- error messages/documentation

Keep modernization findings separate from protocol/correctness findings.

## Finding format

For each finding provide:

- **Title**
- **Category:** correctness / security / compatibility / test / maintainability / modernization
- **Confidence:** high / medium / low
- **Affected files**
- **Current behavior**
- **Why it matters**
- **Proposed smallest fix**
- **Regression tests**
- **Risk of changing it**

At the end, propose an implementation order. Do not implement findings until asked.
