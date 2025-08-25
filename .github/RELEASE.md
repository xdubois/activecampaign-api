# Release Process

This repository uses GitHub Actions for automated releases. The release process is designed to create new versions when changes are merged from the `develop` branch to the `main` branch.

## Automatic Releases

### Trigger Conditions
- **Automatic**: When a merge commit from `develop` to `main` is detected
- **Manual**: Via GitHub Actions workflow dispatch

### Version Increments
The release workflow supports three types of version increments:

- **Major** (X.0.0): Breaking changes that are not backward compatible
- **Minor** (X.Y.0): New features that are backward compatible (default)
- **Patch** (X.Y.Z): Bug fixes that are backward compatible

### Release Workflow

1. **Merge develop → main**: Changes are merged from develop branch to main
2. **Version Calculation**: The workflow automatically calculates the next version based on the selected increment type
3. **Tag Creation**: A new git tag is created (e.g., `v1.1.0`)
4. **Release Notes**: Automatic generation of release notes from commit messages
5. **GitHub Release**: A new GitHub release is published
6. **Composer Update**: The `composer.json` version field is updated

## Manual Release Process

You can manually trigger a release with a specific version increment:

1. Go to **Actions** tab in GitHub
2. Select **Release** workflow
3. Click **Run workflow**
4. Choose the version increment type (major, minor, patch)
5. Click **Run workflow**

## Version Numbering

The project follows [Semantic Versioning](https://semver.org/):

- **1.0.0**: Initial release
- **1.1.0**: Minor increment (new features)
- **1.1.1**: Patch increment (bug fixes)
- **2.0.0**: Major increment (breaking changes)

## Branch Strategy

- **main**: Production-ready code, protected branch
- **develop**: Integration branch for features
- **feature/***: Individual feature branches

## Release Checklist

Before merging to main:

- [ ] All CI checks pass
- [ ] Code review completed
- [ ] Documentation updated
- [ ] Version increment type decided
- [ ] Changelog entries prepared

## Initial Release

For the initial 1.0.0 release:
1. Ensure all features are complete
2. Update documentation
3. Merge develop to main
4. The workflow will automatically create v1.0.0 release