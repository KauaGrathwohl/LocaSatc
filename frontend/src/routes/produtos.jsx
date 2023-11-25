import { useState, useEffect } from "react"
import axios from "axios"
import { Link } from "react-router-dom";
import CaboExtensor from "../../public/images/cabo_extensor.jpg"


export default function Produtos() {

  const [data, setData] = useState(null);
  const [error, setError] = useState(null);

 /* useEffect(() => {
    axios.get('http://localhost/backend-ABP-front/produto').then((response) => {
      setData(response.data);
    });
  }, []);*/
  return (
    <div className="container">
      <div className="bg-black-div/90 border border-white rounded-xl mt-20 py-12 px-8">
        <div className="grid gap-4 grid-cols-3 grid-rows-3">
          <div >
            <div className="w-80" ><img src={CaboExtensor} alt="" /></div>
              <p className="text-blue-600/100" > nome: Cabo Extensor </p>
              <p className="text-blue-600/100"> valor: R$350.00</p>
          </div>
          <div>
            <div className="w-80" ><img src={CaboExtensor} alt="" /></div>
              <p className="text-blue-600/100" >nome: Cabo Extensor </p>
              <p className="text-blue-600/100"> valor: R$350.00</p>
          </div>
        </div>
      </div>
    </div>
  )
}
