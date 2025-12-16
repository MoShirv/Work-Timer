import { BrowserRouter, Routes, Route, Link } from "react-router-dom";
import WorkerAttendance from "./pages/WorkerAttendance";
import AdminDashboard from "./pages/AdminDashboard";

export default function App() {
  return (
    <BrowserRouter>
      <nav style={{ marginBottom: 20 }}>
        <Link to="/">ورود / خروج</Link> |{" "}
        <Link to="/admin">ادمین</Link>
      </nav>

      <Routes>
        <Route path="/" element={<WorkerAttendance />} />
        <Route path="/admin" element={<AdminDashboard />} />
      </Routes>
    </BrowserRouter>
  );
}
