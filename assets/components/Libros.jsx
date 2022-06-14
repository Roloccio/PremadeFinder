import React, { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import Buscador from './Buscador';

const url = `http://127.0.0.1:8000/api/libros?order%5Btitulo%5D=asc`;

const Libros = ({ userGlobal }) => {
  const [jsonData, setJsonData] = useState({});
  const [libros, setLibros] = useState([]);
  const [paginationInfo, setPaginationInfo] = useState({});
  const [multiplePages, setMultiplePages] = useState(false);


  const getInfo = async (url) => {
    try {
      let respuesta = await fetch(url);
      let data = await respuesta.json();
      //console.log(data);
      setJsonData(data);
      
    } catch (error) {
      console.log(error);
    }
  };

  const handleNext = () => {
    getInfo(`http://127.0.0.1:8000${paginationInfo["hydra:next"]}`);
  }

  const handlePrevious = () => {
    getInfo(`http://127.0.0.1:8000${paginationInfo["hydra:previous"]}`);
  }

  useEffect(() => {
    getInfo(url);
  }, []);

  useEffect(() => {
    console.log("effect jsondata");
    //console.log(jsonData["hydra:member"]);
    if ("hydra:member" in jsonData) {
      setLibros(jsonData["hydra:member"]);
    }
    if ("hydra:view" in jsonData) {
      if ("hydra:first" in jsonData["hydra:view"]) {
        setPaginationInfo(jsonData["hydra:view"]);
        setMultiplePages(true);
      } else {
        setPaginationInfo({});
        setMultiplePages(false);
      }
    }
  }, [jsonData])


  return (
    <>
      <section className="librosYbuscador">

        <Buscador setJsonData={setJsonData} />

        {userGlobal?.username ?
          <section className='librosSinLogin'>
            {libros.map((libro) => (
              <Link to={'libro/' + libro.id.toString()} key={libro.id}>
                <div className="libroSinLoguin" key={libro.id}>
                  <h4>{libro.titulo}</h4>
                  <p>{libro.autor}</p>
                </div>
              </Link>
            ))}
          </section>
          :
          <section className='librosConLoguin'>
            {libros.map((libro) => (
              <Link to={'libro/' + libro.id.toString()} key={libro.id}>
                <div className="libroConLoguin" >
                  <h4>{libro.titulo}</h4>
                  <p>{libro.autor}</p>
                </div>
              </Link>
            ))}
          </section>
        }
        {multiplePages ?
          paginationInfo["hydra:first"] === paginationInfo["@id"] ?
            null :
            <button onClick={handlePrevious} className="btn">Anterior</button> :
          null}

        {multiplePages ?
          paginationInfo["@id"] === paginationInfo["hydra:last"] ?
            null :
            <button onClick={handleNext} className="btn">Siguiente</button> :
          null}

      </section>
    </>
  )
}

export default Libros