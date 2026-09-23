# Changelog

## 0.4.0

This release fixes several encoding and decoding bugs and makes the
decoder strict. It contains breaking changes, see below.

### Breaking changes

- Hex strings are encoded byte for byte, leading zero bytes are no longer
  removed (#34, fixes #27). Addresses and hashes starting with `00` are now
  encoded correctly, e.g. `0x0000…0001` (20 bytes) is `94` + 20 bytes instead
  of `01`. Integer quantities passed as hex must not have leading zeros:
  pass `0xcb9d`, not `0x00cb9d`.
- Negative, fractional and out-of-range numbers throw
  `InvalidArgumentException` when encoding (#35). Previously `-1` was
  encoded as `80` and `1.5` as `01`. Pass values above `PHP_INT_MAX` as hex
  strings.
- Invalid hex strings throw `InvalidArgumentException` in `encode()`,
  `decode()`, `Str::encode()` and `Str::decodeHex()` (#33). Only a leading
  `0x` is removed and the rest must be hex digits. `decode()` rejects
  odd-length input. This includes strings such as `0xhello`, which were
  previously encoded as invalid output.
- `decode()` throws `RuntimeException` for invalid RLP:
  - truncated data and list items longer than their list (#32)
  - data after the top-level item (#32)
  - non-canonical lengths: long form for lengths below 56, or lengths with
    leading zero bytes (#36)

### Fixed

- Decoding of lists with a payload of 56 bytes or more followed by other
  items (#30).
- Encoding of strings containing bytes below `0x10`, e.g. `"\x01\x02"` is
  `820102` instead of `12` (#31).
- `decode()` crashed with `TypeError` for long strings declaring a length
  above `PHP_INT_MAX` (#36).
- Encoding floats in exponent form, e.g. `1e15`, crashed with `TypeError`
  (#35).
- `Str::encode('', 'ascii')` returned `00` instead of an empty string before
  PHP 8.2.

### Changed

- CI tests PHP 7.1 to 8.4, and uses current versions of `actions/cache`,
  `actions/checkout` and `codecov/codecov-action` (#30).
- Documentation of accepted inputs, return values and exceptions.
