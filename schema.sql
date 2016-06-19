---api_data
	CREATE TABLE api_data (
		id INTEGER PRIMARY KEY ASC,
		name VARCHAR(100),
		client_id VARCHAR(100),
		client_secret VARCHAR(100),
		headphones_key VARCHAR(100)
	);

	---
	--- Could probably normalize data into 2 tables.
	--- 

---logs
	CREATE TABLE logs (
		id INTEGER PRIMARY KEY ASC,
		type VARCHAR(50),
		epoch INTEGER,
		message VARCHAR(200)
	);z