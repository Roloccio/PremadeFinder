import React, { useEffect, useState } from 'react'
import { useNavigate, useParams } from 'react-router-dom'
import { saveAs } from 'file-saver';

const descargaUrl = `http://127.0.0.1:8000/api/descargar`;
const subirValoracionUrl = `http://127.0.0.1:8000/api/valoraciones`;
const subirComentarioUrl = `http://127.0.0.1:8000/api/comentarios`;

const LibroIndv = ({ userGlobal }) => {

  const navigate = useNavigate();
  const params = useParams();
  const [libro, setLibro] = useState({});
  let suma = 0;
  const [media, setMedia] = useState(0);
  const [totalVal, setTotalVal] = useState(0);
  const [comments, setComments] = useState([]);
  const [selectValue, setSelectValue] = useState("1");
  const [textareaValue, setTextareaValue] = useState("");
  const [yaValorado, setYaValorado] = useState(false);

  const getInfoLibro = async () => {
    try {
      const url = `http://localhost:8000/api/libros/${params.id}`;
      let respuesta = await fetch(url, {
        headers: {
          'Accept': 'application/json',
        },
      });
      let data = await respuesta.json();
      //console.log(data);
      setLibro(data);

    } catch (e) {
      console.log(e);
    }
  }

  const descargarArchivo = async (url, objectToUpload) => {
    //console.log(JSON.stringify(objectToUpload));
    try {
      const response = await fetch(url, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(objectToUpload)
      });
      //console.log(response);
      const file = await response.blob();
      //console.log(file);
      //saveAs(file, "libro.epub"); //este le cambia el nombre a lo que pone en el segundo parámetro. A lo mejor se podría tomar del estado?
      saveAs(file);
    } catch (error) {
      console.log(error);
    }
  };

  const subirElemento = async (url, objectToUpload) => {
    console.log(JSON.stringify(objectToUpload));
    try {
      const response = await fetch(url, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(objectToUpload)
      });
      //console.log(response);
      const data = await response.json();
      //console.log(data);
      getInfoLibro();

    } catch (error) {
      console.log(error);
    }
  };

  const handleDescarga = (e) => {
    e.preventDefault();
    if (!document.getElementById("aceptarTerminos").checked) {
      alert("Debe aceptar los términos");
      return;
    }
    const data = {
      url: libro.url,
      tituloLibro: libro.titulo,
      //url: "../public/books/A/A-ciegas-Claudio-Magris-6216578286703.epub",
      idUser: userGlobal.id,
      idLibro: libro.id,
    }
    //console.log(data);
    descargarArchivo(descargaUrl, data);
  }

  const handleSubirVal = (e) => {
    e.preventDefault();
    const objetoValoracion = {
      puntuacion: parseInt(selectValue),
      autor: `/api/users/${userGlobal.id}`,
      libro: `/api/libros/${libro.id}`,
    }
    subirElemento(subirValoracionUrl, objetoValoracion);
  }

  const handleSelectChange = (e) => {
    //console.log(event.target.value);
    setSelectValue(e.target.value);
  }

  const handleSubirComment = (e) => {
    e.preventDefault();
    if (textareaValue === "") {
      alert("comentario vacío");
      return;
    }
    const objetoComentario = {
      comentario: textareaValue,
      autor: `/api/users/${userGlobal.id}`,
      libro: `/api/libros/${libro.id}`,
    }
    subirElemento(subirComentarioUrl, objetoComentario);
    setTextareaValue("");
  }

  const handleTextareaChange = (e) => {
    setTextareaValue(e.target.value);
  }

  const handleVolver = () => {
    navigate('/');
  }

  const calculoMedia = () => {

    libro.valoraciones.forEach(val => {
      suma += val.puntuacion;
    });
    let valor = suma / totalVal;
    setMedia(valor.toFixed(1));
  }

  const comprobarYaValorado = () => {
    //console.log("hay valoraciones");
    for (const valoracion of libro.valoraciones) {
      //console.log(valoracion); 
      if (valoracion.autor.id === userGlobal.id) {
        //console.log("ya valorado");
        setYaValorado(true);
      }
    }
  }

  useEffect(() => {
    getInfoLibro();
  }, []);

  useEffect(() => {
    if ("id" in libro) {
      setTotalVal(libro.valoraciones?.length);
      setComments(libro.comentarios);
      if ("valoraciones" in libro) {
        comprobarYaValorado();
      }
    }
  }, [libro]);

  useEffect(() => {
    if (totalVal > 0) {
      calculoMedia();
    }

  }, [totalVal]);


  return (
    <section id="libroIndv">

      <section className="datosLibro">

        <div className="img">
          <img src="/img/libro.png" alt={libro.titulo} />
        </div>

        <div className="tituloLibro">
          <h4>{libro.titulo}</h4>
          <p>{libro.autor}</p>

          <p>Puntuación: {media} de 5 <span className="little">({totalVal} valoraciones)</span></p>

          {
            userGlobal ?
              yaValorado ?
                <p style={{ opacity: 0.5 }}>Ya ha valorado este libro</p>
                : <form onSubmit={handleSubirVal} id="formSubirVal">
                  <span className="mr-3">Valorarión: </span>
                  <select value={selectValue} onChange={handleSelectChange} name="val" className="mr-3 custom-select">
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                  </select>
                  <button className="btn">Subir</button>
                </form> :
              null
          }

          {
            userGlobal ?
              <form id="formDescargar" onSubmit={handleDescarga}>
                <div className="form-group">
                  <input type="checkbox" name="aceptar" id="aceptarTerminos" />
                  <label htmlFor="aceptarTerminos">Acepto los términos de descarga</label>
                </div>

                <button className="btn" name="descargar">Descargar</button>
              </form> :
              null
          }

        </div>

      </section>

      <section className="commentsAndStarts">
        {
          userGlobal ?
            <form onSubmit={handleSubirComment} id="formSubirComment">
              <h4>Comentar:</h4>
              <textarea value={textareaValue} onChange={handleTextareaChange}></textarea>
              <button className="btn">Subir</button>
            </form> :
            null
        }

        <section className="comments">
          <h4>Comentarios</h4>
          <table>
            <tbody>
              {comments.map(comentario =>
                <tr key={comentario.id}>
                  <td className="bold">{comentario.autor.username}:</td>
                  <td>{comentario.comentario}</td>
                </tr>
              )}
            </tbody>
          </table>
        </section>

      </section>

      <button onClick={handleVolver} className="btn btn-primary" id="btnVolver">Volver ↩</button>

    </section>
  )
}

export default LibroIndv