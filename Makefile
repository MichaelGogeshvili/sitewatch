perms: dirs
	chmod a+rwx application/sql-logs/
dirs:
	mkdir -pv \
	application/sql-logs/ \
	application/constructors/ \
	application/models/ \
	application/modules/ \
	application/layouts/scripts/ \
	application/forms/Twitter/ \
	application/forms/FormHelper/ \
	application/views/helpers/ \
	application/views/scripts/ \
	application/views/scripts/index \
	application/views/scripts/logs \
	application/views/scripts/admin \
	application/views/scripts/auth \
	application/views/scripts/sites \
	application/views/scripts/maps \
	application/views/scripts/error
 
