# CodeQ.AssetHelpers
A package to provide selection of whole asset collection and tags in Neos content editing that keeps caching in mind.

Here is the escaped Markdown documentation for your `AssetHelper` class:

## CodeQ.Asset EelHelper

The `CodeQ.Asset` EelHelper provides utility methods for working with assets, asset collections, and tags in Neos CMS. It is designed to be used in Eel expressions and supports various operations such as retrieving assets by collection or tag, sorting assets, and converting file sizes to human-readable formats.

### Methods

#### `CodeQ.Asset.getCollectionByIdentifier(string $identifier): ?AssetCollection`
Retrieves an `AssetCollection` by its identifier.

- **Parameters**:
  - `string $identifier`: The identifier of the asset collection.
- **Returns**: The `AssetCollection` object if found, or `null` if not.

---

#### `CodeQ.Asset.getTagByIdentifier(string $identifier): ?Tag`
Retrieves a `Tag` by its identifier.

- **Parameters**:
  - `string $identifier`: The identifier of the tag.
- **Returns**: The `Tag` object if found, or `null` if not.

---

#### `CodeQ.Asset.getAssetsByCollection(mixed $collection, ?string $type = null): array`
Retrieves assets from a given collection, optionally filtered by type.

- **Parameters**:
  - `mixed $collection`: The collection identifier or an `AssetCollection` object.
  - `string\|null $type`: The type of assets to retrieve (\`'image'\` for images, or \`null\` for all assets).
- **Returns**: An array of assets in the collection, sorted by title or filename.
- **Throws**:
  - `InvalidArgumentException` if an invalid type is provided.
  - `InvalidQueryException` if the query fails.

---

#### `CodeQ.Asset.getAssetsByTag(mixed $tag, ?string $type = null): array`
Retrieves assets associated with a given tag, optionally filtered by type.

- **Parameters**:
  - `mixed $tag`: The tag identifier or a `Tag` object.
  - `string\|null $type`: The type of assets to retrieve (\`'image'\` for images, or \`null\` for all assets).
- **Returns**: An array of assets associated with the tag, sorted by title or filename.
- **Throws**:
  - `InvalidArgumentException` if an invalid type is provided.
  - `InvalidQueryException` if the query fails.

---

#### `CodeQ.Asset.humanReadableFileSize(int $size): string`
Converts a file size in bytes to a human-readable format.

- **Parameters**:
  - `int $size`: The file size in bytes.
- **Returns**: A string representing the file size in a human-readable format (e.g., \`1.2 MB\`, \`512 KB\`).

---

### Usage Example

You can use the `CodeQ.Asset` EelHelper in your Fusion code as follows:

```fusion
prototype(Your.Package:Component) {
    assets = ${CodeQ.Asset.getAssetsByCollection('your-collection-identifier')}
    humanReadableSize = ${CodeQ.Asset.humanReadableFileSize(1048576)} # Outputs "1.0 MB"
}
```

## Notes
- The methods are designed to handle both identifiers and object instances for collections and tags.
```
