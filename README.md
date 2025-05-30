# CodeQ.AssetHelpers
A package to provide selection of whole asset collection and tags in Neos content editing that keeps caching in mind.

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

### Notes
- The methods are designed to handle both identifiers and object instances for collections and tags.

## CodeQ.AssetCacheTag EelHelper

The `CodeQ.AssetCacheTag` EelHelper provides utility methods for generating cache tags for assets, asset collections, and tags in Neos CMS. These methods are designed to be used in Eel expressions and help manage caching effectively.

### Methods

#### `CodeQ.AssetCacheTag.assetTag(array|Asset $assets): array<string>`
Generates cache tags for a given asset or an array of assets.

- **Parameters**:
  - `array|Asset $assets`: A single `Asset` object or an array of `Asset` objects.
- **Returns**: An array of cache tags for the provided assets.

---

#### `CodeQ.AssetCacheTag.assetCollectionTag(AssetCollection $assetCollection): string`
Generates a cache tag for a given asset collection.

- **Parameters**:
  - `AssetCollection $assetCollection`: The asset collection for which the cache tag is generated.
- **Returns**: A string representing the cache tag for the asset collection.

---

#### `CodeQ.AssetCacheTag.assetTagsCachingTag(array|Tag $tags): array<string>`
Generates cache tags for a given tag or an array of tags.

- **Parameters**:
  - `array|Tag $tags`: A single `Tag` object or an array of `Tag` objects.
- **Returns**: An array of cache tags for the provided tags.

---

### Usage Example

You can use the `CodeQ.AssetCacheTag` EelHelper in your Fusion code as follows:

```fusion
prototype(Your.Package:Component) {
    @cache {
        entryTags {
            0 = ${CodeQ.AssetCacheTag.assetTag(asset)}
            1 = ${CodeQ.AssetCacheTag.assetCollectionTag(assetCollection)}
            2 = ${CodeQ.AssetCacheTag.assetTagsCachingTag(tags)}
        }
    }
}
