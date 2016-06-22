---api_data
	CREATE TABLE api_data (
		id 					INTEGER PRIMARY KEY ASC,
		client_id 			VARCHAR(100),
		client_secret 		VARCHAR(100),
		access_token 		VARCHAR(100),
		refresh_token 		VARCHAR(100),
		headphones_key 		VARCHAR(100),
		headphones_host 	VARCHAR(100)
	);

---logs
	CREATE TABLE logs (
		id 					INTEGER PRIMARY KEY ASC,
		type 				VARCHAR(50),
		epoch 				INTEGER,
		message 			VARCHAR(200)
	);

---cache
	CREATE TABLE cache (
		id 					INTEGER PRIMARY KEY ASC,
		artist 				VARCHAR(150),
		album 				VARCHAR(150)
	);