# Initial Release Setup Guide

This document provides step-by-step instructions for creating the initial 1.0.0 release of the ActiveCampaign API PHP library.

## Prerequisites

Before creating the initial release, ensure:

- [ ] All core functionality is implemented and tested
- [ ] Documentation is complete and up-to-date
- [ ] CI pipeline passes successfully
- [ ] Code review is completed
- [ ] Release workflow is properly configured

## Steps for Initial 1.0.0 Release

### 1. Prepare the Release Branch

```bash
# Switch to develop branch
git checkout develop

# Make final adjustments if needed
# Update version in composer.json to "1.0.0" (optional, workflow will handle this)

# Commit any final changes
git add .
git commit -m "Prepare for initial 1.0.0 release"
git push origin develop
```

### 2. Create Release Pull Request

Create a PR from `develop` to `main` with:

**Title**: `Release v1.0.0 - Initial Release`

**Description**:
```markdown
## Release v1.0.0 - Initial Release

### Features
- Complete ActiveCampaign API v3 integration
- Support for Deals, Contacts, Accounts, Custom Objects, and Custom Fields
- PHP 7.4+ compatibility
- Comprehensive error handling
- Automated release pipeline

### API Coverage
- ✅ Deals management
- ✅ Contacts management  
- ✅ Accounts management
- ✅ Custom Objects management
- ✅ Custom Fields management

### Technical Details
- PSR-4 autoloading
- Guzzle HTTP client integration
- Comprehensive exception handling
- Clean, object-oriented API design

### Breaking Changes
N/A - Initial release

### Migration Guide
N/A - Initial release
```

### 3. Merge and Release

1. **Review PR**: Ensure all checks pass and code review is complete
2. **Merge PR**: Merge the PR from develop to main
3. **Automatic Release**: The GitHub Actions workflow will automatically:
   - Detect the merge from develop
   - Create version tag `v1.0.0`
   - Generate release notes
   - Publish GitHub release
   - Update composer.json version

### 4. Verify Release

After the workflow completes:

- [ ] Check that tag `v1.0.0` was created
- [ ] Verify GitHub release was published
- [ ] Confirm release notes are accurate
- [ ] Test Composer installation: `composer require xdubois/activecampaign-api`

## Alternative: Manual Release

If you prefer to trigger the release manually:

1. Go to GitHub → Actions → Release workflow
2. Click "Run workflow"
3. Select branch: `main`
4. Choose version type: `major` (for 1.0.0)
5. Click "Run workflow"

## Post-Release Tasks

After successful release:

- [ ] Update README badges (if any)
- [ ] Announce release in relevant channels
- [ ] Update project documentation
- [ ] Plan next development cycle

## Troubleshooting

### Workflow Fails
- Check GitHub Actions logs
- Verify GITHUB_TOKEN permissions
- Ensure main branch is not protected from pushes by Actions

### Tag Not Created
- Verify the merge commit message contains "Merge" and "develop"
- Check workflow logs for errors
- Ensure no existing tag conflicts

### Composer Update Fails
- Check if jq is available in workflow
- Verify composer.json syntax
- Review git configuration in workflow

## Version Numbering Going Forward

After 1.0.0:
- **Patch releases** (1.0.1): Bug fixes
- **Minor releases** (1.1.0): New features, backward compatible
- **Major releases** (2.0.0): Breaking changes

Each release should follow the same develop → main merge process.