import { useEffect, useState } from "react";
import api from "../services/api";

export default function AdminDashboard() {
  const [workers, setWorkers] = useState([]);
  const [name, setName] = useState("");

  const loadWorkers = () => {
    api.get("/workers").then(res => setWorkers(res.data));
  };

  useEffect(() => {
    loadWorkers();
  }, []);

  const addWorker = async () => {
    if (!name) return;
    await api.post("/workers", { name });
    setName("");
    loadWorkers();
  };

  const deleteWorker = async (id) => {
    await api.delete(`/workers/${id}`);
    loadWorkers();
  };

  return (
    <div style={{ padding: 20 }}>
      <h2>داشبورد ادمین</h2>

      <input
        placeholder="نام نیرو"
        value={name}
        onChange={e => setName(e.target.value)}
      />
      <button onClick={addWorker}>اضافه</button>

      <ul>
        {workers.map(w => (
          <li key={w.id}>
            {w.name}
            <button onClick={() => deleteWorker(w.id)}>حذف</button>
          </li>
        ))}
      </ul>
    </div>
  );
}
