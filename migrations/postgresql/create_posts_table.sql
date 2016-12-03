CREATE TABLE posts (
    id SERIAL,
    title VARCHAR(50),
    body TEXT,
    created timestamp DEFAULT NULL,
    modified timestamp DEFAULT NULL
);
