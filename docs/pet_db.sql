CREATE TABLE users (
    id int(11) NOT NULL,
    name varchar(100) NOT NULL,
    email varchar(255) NOT NULL,
    password varchar(255) NOT NULL,
    address varchar(255) DEFAULT NULL,
    phone varchar(20) DEFAULT NULL,
    created_at datetime NOT NULL DEFAULT current_timestamp(),
    updated_at datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

CREATE TABLE pets (
    id int(11) NOT NULL,
    name varchar(255) NOT NULL,
    animal_id int(11) NOT NULL,
    image_name varchar(255) NOT NULL,
    description text NOT NULL,
    user_id int(11) DEFAULT NULL,
    created_at datetime NOT NULL DEFAULT current_timestamp(),
    updated_at datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

CREATE TABLE comments (
    id int(11) NOT NULL,
    pet_id int(11) NOT NULL,
    image_name varchar(255) NOT NULL,
    comment TEXT NOT NULL,
    created_at datetime NOT NULL DEFAULT current_timestamp(),
    updated_at datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

CREATE TABLE rewards (
    id int(11) NOT NULL,
    pet_id int(11) NOT NULL,
    user_id int(11) NOT NULL,
    amount int(11) NOT NULL,
    created_at datetime NOT NULL DEFAULT current_timestamp(),
    updated_at datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;