import { useEffect, useState } from "react";
import api from "../services/api";

export default function WorkerAttendance() {
  const [workers, setWorkers] = useState([]);
  const [workerId, setWorkerId] = useState("");
  const [message, setMessage] = useState("");

  useEffect(() => {
    api.get("/workers").then(res => setWorkers(res.data));
  }, []);

  const checkIn = async () => {
    if (!workerId) return setMessage("لطفا نیرو را انتخاب کنید");
    try {
      const res = await api.post("/attendance/check-in", {
        worker_id: workerId,
      });
      setMessage(res.data.message);
    } catch (e) {
      setMessage(e.response?.data?.message || "خطا");
    }
  };

  const checkOut = async () => {
    if (!workerId) return setMessage("لطفا نیرو را انتخاب کنید");
    try {
      const res = await api.post("/attendance/check-out", {
        worker_id: workerId,
      });
      setMessage(res.data.message);
    } catch (e) {
      setMessage(e.response?.data?.message || "خطا");
    }
  };

  return (
    <div style={{ padding: 20 }}>
      <h2>ورود / خروج نیرو</h2>

      <select value={workerId} onChange={e => setWorkerId(e.target.value)}>
        <option value="">انتخاب نیرو</option>
        {workers.map(w => (
          <option key={w.id} value={w.id}>{w.name}</option>
        ))}
      </select>

      <div style={{ marginTop: 10 }}>
        <button onClick={checkIn}>ورود</button>
        <button onClick={checkOut} style={{ marginRight: 10 }}>خروج</button>
      </div>

      {message && <p>{message}</p>}
    </div>
  );
}
