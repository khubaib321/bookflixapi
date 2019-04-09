INSERT INTO users (id, name, email, phone1) VALUES (uuid(), 'Khubaib Afzal', 'khubbi101@gmail.com', '+923228422432');
INSERT INTO books (id, name, author, publisher, year, description, no_of_pages, category) values (uuid(), 'The New Psychology of Success', 'Carol S. Dweck', 'Random House', '2019', 'A book on psychology by some author and some publisher. Created first for testing purpose.', 147, 'Mindset');

SELECT 
    *
FROM
    books;