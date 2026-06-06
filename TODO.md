# TODO: Add Featured Jobs Functionality

## Tasks
- [x] Add "Is Featured" checkbox to admin/add-job.php
- [ ] Add "Is Featured" checkbox to admin/edit-job.php (with pre-check)
- [ ] Update admin/submit-job.php to handle is_featured field
- [ ] Update admin/update-job.php to handle is_featured field
- [ ] Modify index.php to filter jobs by is_featured = 1 and display Featured badge
- [ ] Test the functionality

## Notes
- Database already has `is_featured` column (tinyint(1), default 0)
- Only featured jobs should be shown on index page
- Featured jobs should display a "Featured" badge
