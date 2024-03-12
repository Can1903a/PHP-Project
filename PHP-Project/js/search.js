import React, { useState, useEffect } from 'react';
import axios from 'axios'; // axios kütüphanesini kullanarak AJAX isteği göndermek için

const SearchBox = () => {
  const [searchTerm, setSearchTerm] = useState('');
  const [searchResults, setSearchResults] = useState([]);

  useEffect(() => {
    if (searchTerm !== '') {
      // Arama terimini PHP endpointine göndermek için AJAX isteği gönder
      axios.get(`/PHP-Project/search.php?q=${searchTerm}`)
        .then(response => {
          setSearchResults(response.data);
        })
        .catch(error => {
          console.error('Error fetching search results:', error);
        });
    } else {
      setSearchResults([]);
    }
  }, [searchTerm]);

  const handleChange = event => {
    setSearchTerm(event.target.value);
  };

  return (
    <form id="searchForm">
      <input
        type="text"
        id="searchInput"
        placeholder="Search..."
        value={searchTerm}
        onChange={handleChange}
      />
      <div id="searchResults">
        {searchResults.map((item, index) => (
          <div key={index}>{item}</div>
        ))}
      </div>
    </form>
  );
};

export default SearchBox;
