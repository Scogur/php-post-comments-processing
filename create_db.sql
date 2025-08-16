CREATE TABLE posts (
    id NUMBER PRIMARY KEY,
    user_id NUMBER NOT NULL,
    title VARCHAR2(255) NOT NULL,
    body CLOB NOT NULL
);

CREATE TABLE comments (
    id NUMBER PRIMARY KEY,
    post_id NUMBER NOT NULL,
    name VARCHAR2(255) NOT NULL,
    email VARCHAR2(255) NOT NULL,
    body CLOB NOT NULL,
    CONSTRAINT fk_post FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE
);
