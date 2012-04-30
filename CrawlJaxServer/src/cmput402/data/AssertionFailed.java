package cmput402.data;

public class AssertionFailed {
	public String id;
	public String error;
	@Override
	public String toString() {
		return "AssertionFailed [id=" + id + ", error=" + error + "]";
	}
}
