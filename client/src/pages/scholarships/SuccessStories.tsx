import CategoryStoriesPage from "@/components/CategoryStoriesPage";

const SuccessStories = () => {
  return (
    <CategoryStoriesPage
      defaultCategory="Success Stories"
      title="Success Stories"
      description="Celebrating the achievements and triumphs of athletes who have made it against all odds."
      showAllCategories={false}
    />
  );
};

export default SuccessStories;