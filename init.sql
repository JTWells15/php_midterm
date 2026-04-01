CREATE TABLE IF NOT EXISTS authors (
    id SERIAL PRIMARY KEY,
    author VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS categories (
    id SERIAL PRIMARY KEY,
    category VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS quotes (
    id SERIAL PRIMARY KEY,
    quote TEXT NOT NULL,
    author_id INTEGER NOT NULL,
    category_id INTEGER NOT NULL,
    CONSTRAINT fk_author
        FOREIGN KEY(author_id)
        REFERENCES authors(id)
        ON DELETE RESTRICT,
    CONSTRAINT fk_category
        FOREIGN KEY(category_id)
        REFERENCES categories(id)
        ON DELETE RESTRICT
);

INSERT INTO authors (author) VALUES
    ('Albert Einstein'),
    ('Maya Angelou')
ON CONFLICT DO NOTHING;

INSERT INTO categories (category) VALUES
    ('Inspirational'),
    ('Wisdom')
ON CONFLICT DO NOTHING;

INSERT INTO quotes (quote, author_id, category_id) VALUES
    ('Life is like riding a bicycle. To keep your balance you must keep moving.', 1, 2),
    ('You will face many defeats in life, but never let yourself be defeated.', 2, 1)
ON CONFLICT DO NOTHING;